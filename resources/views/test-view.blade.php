<?php

// 1. INSTALL PACKAGE TERLEBIH DAHULU
// composer require phpoffice/phpspreadsheet

// 2. MIGRATION - database/migrations/xxxx_create_users_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};

// 3. MODEL - app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}

// 4. SERVICE CLASS - app/Services/ExcelImportService.php
namespace App\Services;

use App\Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class ExcelImportService
{
    public function importUsers($filePath)
    {
        try {
            // Load spreadsheet
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Remove header row
            $header = array_shift($rows);

            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($rows as $index => $row) {
                $rowNumber = $index + 2; // +2 karena index 0 dan header

                try {
                    // Skip empty rows
                    if (empty(array_filter($row))) {
                        continue;
                    }

                    // Validasi data
                    if (count($row) < 4) {
                        $errors[] = "Baris {$rowNumber}: Data tidak lengkap";
                        $errorCount++;
                        continue;
                    }

                    $username = trim($row[0]);
                    $name = trim($row[1]);
                    $email = trim($row[2]);
                    $password = trim($row[3]);

                    // Validasi field tidak boleh kosong
                    if (empty($username) || empty($name) || empty($email) || empty($password)) {
                        $errors[] = "Baris {$rowNumber}: Ada field yang kosong";
                        $errorCount++;
                        continue;
                    }

                    // Validasi format email
                    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $errors[] = "Baris {$rowNumber}: Format email tidak valid ({$email})";
                        $errorCount++;
                        continue;
                    }

                    // Cek duplikat username
                    if (User::where('username', $username)->exists()) {
                        $errors[] = "Baris {$rowNumber}: Username '{$username}' sudah ada";
                        $errorCount++;
                        continue;
                    }

                    // Cek duplikat email
                    if (User::where('email', $email)->exists()) {
                        $errors[] = "Baris {$rowNumber}: Email '{$email}' sudah ada";
                        $errorCount++;
                        continue;
                    }

                    // Insert user
                    User::create([
                        'username' => $username,
                        'name' => $name,
                        'email' => $email,
                        'password' => $password, // Akan di-hash otomatis oleh model
                    ]);

                    $successCount++;

                } catch (Exception $e) {
                    $errors[] = "Baris {$rowNumber}: Error - " . $e->getMessage();
                    $errorCount++;
                    Log::error("Import error on row {$rowNumber}: " . $e->getMessage());
                }
            }

            DB::commit();

            return [
                'success' => true,
                'total_processed' => $successCount + $errorCount,
                'success_count' => $successCount,
                'error_count' => $errorCount,
                'errors' => $errors
            ];

        } catch (Exception $e) {
            DB::rollback();
            Log::error('Excel import failed: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Gagal mengimpor file: ' . $e->getMessage(),
                'errors' => []
            ];
        }
    }
}

// 5. CONTROLLER - app/Http/Controllers/UserImportController.php
namespace App\Http\Controllers;

use App\Services\ExcelImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserImportController extends Controller
{
    protected $excelImportService;

    public function __construct(ExcelImportService $excelImportService)
    {
        $this->excelImportService = $excelImportService;
    }

    public function showImportForm()
    {
        return view('users.import');
    }

    public function import(Request $request)
    {
        // Validasi file
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240' // max 10MB
        ]);

        try {
            // Upload file
            $file = $request->file('excel_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('imports', $fileName, 'local');
            $fullPath = storage_path('app/' . $filePath);

            // Import data
            $result = $this->excelImportService->importUsers($fullPath);

            // Hapus file setelah import
            Storage::disk('local')->delete($filePath);

            if ($result['success']) {
                $message = "Import berhasil! {$result['success_count']} user berhasil diimpor";

                if ($result['error_count'] > 0) {
                    $message .= ", {$result['error_count']} baris gagal diimpor.";
                }

                return redirect()->back()->with('success', $message)->with('errors', $result['errors']);
            } else {
                return redirect()->back()->with('error', $result['message']);
            }

        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

// 6. ROUTES - routes/web.php
use App\Http\Controllers\UserImportController;

Route::get('/users/import', [UserImportController::class, 'showImportForm'])->name('users.import.form');
Route::post('/users/import', [UserImportController::class, 'import'])->name('users.import');

// 7. VIEW - resources/views/users/import.blade.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .drag-drop-area {
            border: 2px dashed #cbd5e0;
            border-radius: 8px;
            padding: 3rem 2rem;
            text-align: center;
            background-color: #f8f9fa;
            transition: all 0.3s ease;
            cursor: pointer;
            position: relative;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .drag-drop-area:hover {
            border-color: #007bff;
            background-color: #e6f3ff;
        }

        .drag-drop-area.drag-over {
            border-color: #007bff;
            background-color: #e6f3ff;
            border-style: solid;
            transform: scale(1.02);
        }

        .drag-drop-area.has-file {
            border-color: #28a745;
            background-color: #d4edda;
        }

        .file-input-hidden {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .upload-icon {
            font-size: 3rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }

        .drag-over .upload-icon {
            color: #007bff;
            animation: bounce 0.6s ease-in-out;
        }

        .has-file .upload-icon {
            color: #28a745;
        }

        @keyframes bounce {
            0%, 20%, 60%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            80% { transform: translateY(-5px); }
        }

        .file-info {
            display: none;
            background: white;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid #dee2e6;
            margin-top: 1rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .file-info.show {
            display: block;
        }

        .remove-file {
            color: #dc3545;
            cursor: pointer;
            float: right;
            font-size: 1.2rem;
        }

        .remove-file:hover {
            color: #a71d2a;
        }

        .upload-text {
            font-size: 1.1rem;
            color: #495057;
            margin: 0.5rem 0;
        }

        .upload-subtext {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .progress {
            display: none;
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4><i class="fas fa-file-excel me-2"></i>Import Users from Excel</h4>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            </div>
                        @endif

                        @if(session('errors') && count(session('errors')) > 0)
                            <div class="alert alert-warning">
                                <strong><i class="fas fa-exclamation-triangle me-2"></i>Detail Error:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach(session('errors') as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li><i class="fas fa-times me-2"></i>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data" id="upload-form">
                            @csrf

                            <div class="mb-4">
                                <div class="drag-drop-area" id="drag-drop-area">
                                    <input type="file" class="file-input-hidden" id="excel_file" name="excel_file"
                                           accept=".xlsx,.xls,.csv" required>

                                    <div class="upload-content">
                                        <div class="upload-icon">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                        </div>
                                        <div class="upload-text">
                                            <strong>Drag & Drop file Excel di sini</strong>
                                        </div>
                                        <div class="upload-subtext">
                                            atau <span style="color: #007bff; font-weight: 500;">klik untuk browse</span>
                                        </div>
                                        <div class="upload-subtext mt-2">
                                            <small>Format: .xlsx, .xls, .csv (Max: 10MB)</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="file-info" id="file-info">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-file-excel text-success me-2"></i>
                                            <span id="file-name"></span>
                                            <small class="text-muted ms-2">(<span id="file-size"></span>)</small>
                                        </div>
                                        <span class="remove-file" id="remove-file">
                                            <i class="fas fa-times"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="progress" id="upload-progress">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated"
                                         role="progressbar" style="width: 0%"></div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="card">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Format Excel yang diharapkan:</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-sm table-bordered mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th><i class="fas fa-user me-1"></i>Kolom A</th>
                                                    <th><i class="fas fa-id-card me-1"></i>Kolom B</th>
                                                    <th><i class="fas fa-envelope me-1"></i>Kolom C</th>
                                                    <th><i class="fas fa-key me-1"></i>Kolom D</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><strong>Username</strong></td>
                                                    <td><strong>Name</strong></td>
                                                    <td><strong>Email</strong></td>
                                                    <td><strong>Password</strong></td>
                                                </tr>
                                                <tr class="table-light">
                                                    <td>john_doe</td>
                                                    <td>John Doe</td>
                                                    <td>john@example.com</td>
                                                    <td>password123</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg" id="submit-btn" disabled>
                                    <i class="fas fa-upload me-2"></i>Import Users
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dragDropArea = document.getElementById('drag-drop-area');
            const fileInput = document.getElementById('excel_file');
            const fileInfo = document.getElementById('file-info');
            const fileName = document.getElementById('file-name');
            const fileSize = document.getElementById('file-size');
            const removeFileBtn = document.getElementById('remove-file');
            const submitBtn = document.getElementById('submit-btn');
            const uploadForm = document.getElementById('upload-form');
            const uploadProgress = document.getElementById('upload-progress');
            const progressBar = uploadProgress.querySelector('.progress-bar');

            // Prevent default drag behaviors
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dragDropArea.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });

            // Highlight drop area when item is dragged over it
            ['dragenter', 'dragover'].forEach(eventName => {
                dragDropArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dragDropArea.addEventListener(eventName, unhighlight, false);
            });

            // Handle dropped files
            dragDropArea.addEventListener('drop', handleDrop, false);

            // Handle click to browse
            dragDropArea.addEventListener('click', () => fileInput.click());

            // Handle file input change
            fileInput.addEventListener('change', function(e) {
                if (e.target.files.length > 0) {
                    handleFiles(e.target.files);
                }
            });

            // Remove file
            removeFileBtn.addEventListener('click', function() {
                removeFile();
            });

            // Handle form submission with progress
            uploadForm.addEventListener('submit', function(e) {
                if (fileInput.files.length > 0) {
                    showProgress();
                }
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            function highlight() {
                dragDropArea.classList.add('drag-over');
            }

            function unhighlight() {
                dragDropArea.classList.remove('drag-over');
            }

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                handleFiles(files);
            }

            function handleFiles(files) {
                if (files.length > 0) {
                    const file = files[0];

                    // Validate file type
                    const allowedTypes = [
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-excel',
                        'text/csv'
                    ];

                    if (!allowedTypes.includes(file.type) && !file.name.match(/\.(xlsx|xls|csv)$/i)) {
                        alert('Format file tidak didukung. Gunakan .xlsx, .xls, atau .csv');
                        return;
                    }

                    // Validate file size (10MB)
                    if (file.size > 10 * 1024 * 1024) {
                        alert('Ukuran file terlalu besar. Maksimal 10MB');
                        return;
                    }

                    // Update file input
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    fileInput.files = dt.files;

                    // Show file info
                    showFileInfo(file);
                }
            }

            function showFileInfo(file) {
                fileName.textContent = file.name;
                fileSize.textContent = formatFileSize(file.size);
                fileInfo.classList.add('show');
                dragDropArea.classList.add('has-file');
                submitBtn.disabled = false;

                // Update drag drop area content
                const uploadContent = dragDropArea.querySelector('.upload-content');
                uploadContent.innerHTML = `
                    <div class="upload-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="upload-text text-success">
                        <strong>File siap untuk diupload!</strong>
                    </div>
                    <div class="upload-subtext">
                        Klik "Import Users" untuk memulai proses import
                    </div>
                `;
            }

            function removeFile() {
                fileInput.value = '';
                fileInfo.classList.remove('show');
                dragDropArea.classList.remove('has-file');
                submitBtn.disabled = true;

                // Reset drag drop area content
                const uploadContent = dragDropArea.querySelector('.upload-content');
                uploadContent.innerHTML = `
                    <div class="upload-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div class="upload-text">
                        <strong>Drag & Drop file Excel di sini</strong>
                    </div>
                    <div class="upload-subtext">
                        atau <span style="color: #007bff; font-weight: 500;">klik untuk browse</span>
                    </div>
                    <div class="upload-subtext mt-2">
                        <small>Format: .xlsx, .xls, .csv (Max: 10MB)</small>
                    </div>
                `;
            }

            function showProgress() {
                uploadProgress.style.display = 'block';
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';

                // Simulate progress (since we can't track real progress easily with form submission)
                let progress = 0;
                const interval = setInterval(() => {
                    progress += Math.random() * 15;
                    if (progress > 90) progress = 90;
                    progressBar.style.width = progress + '%';

                    if (progress >= 90) {
                        clearInterval(interval);
                        progressBar.style.width = '100%';
                        setTimeout(() => {
                            progressBar.classList.remove('progress-bar-animated');
                        }, 500);
                    }
                }, 200);
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        });
    </script>
</body>
</html>
