<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Import\ExcelImport;
use App\Import\Template;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class AkunSiswa extends Controller
{
    protected $excelImportService;
    public function __construct(ExcelImport $excelImportService)
    {
        $this->excelImportService = $excelImportService;
    }

    public function index()
    {
        return view('data.akun-siswa');
    }

    public function getData()
    {
        $data = User::role('user')->with('siswa', 'siswa.kelas', 'siswa.jurusan')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('siswa.jurusan.name', function ($row) {
                return strtoupper($row->siswa->jurusan->name ?? '-');
            })
            ->addColumn('action', function ($row) {
                $btn = '<button class="btn mx-2 btn-sm btn-primary">Edit</button>';
                $btn .= '<button class="btn mx-2 btn-sm btn-danger">Delete</button>';
                return $btn;
            })
            ->make(true);
    }

    public function getTemplate()
    {
        try {
            $template = new Template();
            $template->getTemplateSiswa();

            return;
        } catch (Exception $e) {
            Log::error('failed get siswa template ' . $e->getMessage());
            return;
        }
    }

    public function import(Request $request)
    {
        // Validasi file
        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240' // max 10MB
        ]);

        try {
            // Validasi file
            $request->validate([
                'excel_file' => 'required|file|mimes:xlsx,xls,csv|max:10240' // max 10MB
            ]);

            // Upload file
            $file = $request->file('excel_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('imports', $fileName, 'local');
            $fullPath = storage_path('app/' . $filePath);

            // Import data
            $result = $this->excelImportService->importSiswa(Storage::path($filePath));

            // Hapus file setelah import
            Storage::disk('local')->delete($filePath);

            if ($result['success']) {
                $message = "Import berhasil! {$result['success_count']} user berhasil diimpor";

                if ($result['error_count'] > 0) {
                    $message .= ", {$result['error_count']} baris gagal diimpor.";
                }

                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => [
                        'total_processed' => $result['total_processed'],
                        'success_count' => $result['success_count'],
                        'error_count' => $result['error_count'],
                        'errors' => $result['errors']
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                    'errors' => []
                ], 400);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi file gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'errors' => []
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $user = User::create([
                'name' => $request->nama,
                'username' => $request->username,
                'password' => bcrypt($request->password),
            ]);

            $user->assignRole('user');

            $user->siswa()->create([
                'kelas_id' => $request->kelas,
                'jurusan_id' => $request->jurusan,
                'name' => $request->nama,
                'nisn' => $request->nisn,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Data Success Create',
                'data' => $request->all()
            ]);
        } catch (\Exception $e) {
            Log::error("message: " . $e->getMessage());
            return response()->json([
                'status' => 500,
                'message' => 'Server Error',
                'data' => $e->getMessage()
            ]);
        }
    }
}
