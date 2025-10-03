<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Import\ExcelImport;
use App\Models\Guru as ModelsGuru;
use Illuminate\Support\Facades\DB;
use App\Models\GuruPivot;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class Guru extends Controller
{
    protected $excelImportService;
    public function __construct(ExcelImport $excelImportService)
    {
        $this->excelImportService = $excelImportService;
    }

    public function index()
    {
        return view('data.data-guru');
    }

    public function getData(Request $request)
    {
        $data = ModelsGuru::with('user', 'pivot.kelas', 'pivot.jurusan')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }

    public function getTemplate()
    {
        try {
            $template = new \App\Import\Template();

            $template->getTemplateGuru();

            return response()->json([
                'response' => 200,
                'message' => 'Template berhasil diunduh',
            ]);
        } catch (\Exception $e) {
            Log::error('Error downloading template: ' . $e->getMessage());
            return response()->json([
                'response' => 500,
                'message' => 'Terjadi kesalahan saat mengunduh template: ' . $e->getMessage(),
            ]);
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
            $result = $this->excelImportService->importGuru(Storage::path($filePath));

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

            User::create([
                'username' => $request->username,
                'name' => $request->nama,
                'email' => $request->email,
                'password' => $request->password, // Akan di-hash otomatis oleh model
            ])->assignRole('teacher')->guru()->create([
                'name' => $request->nama,
                'nip' => $request->nip
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Data Success Create',
                'data' => $request->all()
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('error validasi data ' . $e->errors());

            return response()->json([
                'status' => 422,
                'message' => 'input data harus lengkap',
                'data' => $request->all()
            ]);
        } catch (Exception $e) {
            Log::error('error dalam memasukkan data guru ' . $e->getMessage());

            return response()->json([
                'status' => 500,
                'message' => 'error'
            ]);
        }
    }

    public function edit($id)
    {
        $guru = ModelsGuru::with('user')->find($id);

        // dd($guru);

        return view('data.guru-edit', compact(['guru', 'id']));
    }

    public function update(Request $request)
    {
        try {

            $guru = ModelsGuru::with('user')->find($request->idGuru);

            $userImage = $guru->user->foto_profile;

            if ($request->hasFile('image')) {

                if ($userImage) {
                    $oldImagePath = 'public/profile-images/' . $userImage;
                    if (Storage::exists($oldImagePath)) {
                        Storage::delete($oldImagePath);
                    }
                }

                $imageName = time() . generateRandomString(12) . '.' . $request->image->extension();
                $request->image->storeAs('profile-images', $imageName, 'public');

                // Update user profile image
                $guru->user->foto_profile = $imageName;
                $guru->user->save();

                // Get the URL for the new image
                $imageUrl = Storage::url('profile-images/' . $imageName);
            }


            return response()->json([
                'success' => true,
                'message' => 'Profile berhasil diperbarui!',
                'url' => asset('storage/profile-images/' . $guru->user->foto_profile),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Di controller Laravel Anda
    public function getSingleData($id)
    {
        $imageUrl = '';
        $data = ModelsGuru::with('user')->find($id);
        if ($data->user->foto_profile) {
            $imageUrl = asset('storage/profile-images/' . $data->user->foto_profile);
        } else {
            $imageUrl = asset('img/default-profile.png');
        }

        return response()->json([
            'success' => true,
            'image' => $imageUrl,
            'nip' => $data->nip,
            'name' => $data->name,
            'email' => $data->email ?? '-'
        ]);
    }

    public function delete($id)
    {
        try {
            $data = ModelsGuru::with('user')->find($id);
            if ($data->user->foto_profile) {
                $oldImagePath = 'public/profile-images/' . $data->user->foto_profile;
                if (Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }
            }
            $data->user->delete();
            $data->delete();

            return response()->json([
                'success' => true,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false
            ]);
        }
    }

    public function setClass(Request $request)
    {
        // Validasi data yang diterima
        $request->validate([
            'current_class_ids' => 'nullable|array',
            'current_class_ids.*' => 'integer', // ID dari GuruPivot
            'deleted_class_ids' => 'nullable|array',
            'deleted_class_ids.*' => 'integer', // ID dari GuruPivot
            'idGuru' => 'required|integer', // ID Guru
        ]);

        $currentIds = $request->input('current_class_ids', []);
        $deletedIds = $request->input('deleted_class_ids', []);
        $newClasses = $request->input('new_classes', []); // Data kelas baru
        $guruId = $request->input('idGuru');

        try {
            DB::beginTransaction();

            // 1. Soft Delete kelas yang dihapus (tombol di kolom "Dihapus")
            if (!empty($deletedIds)) {
                // Menggunakan query builder untuk soft delete multiple records
                // karena delete() pada collection tidak memicu SoftDeletes trait secara massal
                GuruPivot::whereIn('id', $deletedIds)->delete();
            }

            // 2. Restore/Un-delete kelas yang dikembalikan (tombol di kolom "Kelas Diajar")
            if (!empty($currentIds)) {
                // Restore kelas yang sebelumnya soft-deleted dan kini kembali aktif
                GuruPivot::withTrashed()
                        ->whereIn('id', $currentIds)
                        ->restore();
            }

            // 3. Tambahkan kelas baru
            // Data kelas baru dikirim dalam format [{kelas_id, jurusan_id}, ...]
            if (!empty($newClasses)) {
                $newPivots = [];
                foreach ($newClasses as $newClass) {
                    // Cek apakah data ini sudah pernah ada dan ter-soft delete
                    $existingPivot = GuruPivot::withTrashed()
                        ->where('guru_id', $guruId)
                        ->where('kelas_id', $newClass['kelas_id'])
                        ->where('jurusan_id', $newClass['jurusan_id'])
                        ->first();

                    if ($existingPivot) {
                        // Jika ada dan soft-deleted, restore
                        if ($existingPivot->trashed()) {
                            $existingPivot->restore();
                        }
                        // Jika ada dan tidak soft-deleted, tidak perlu dilakukan apa-apa.
                        // Cuma logikanya data ini harusnya sudah ada di currentIds, tapi ini sebagai fallback.
                    } else {
                        // Jika benar-benar baru, buat record baru
                        $newPivots[] = [
                            'guru_id' => $guruId,
                            'kelas_id' => $newClass['kelas_id'],
                            'jurusan_id' => $newClass['jurusan_id'],
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    }
                }

                if (!empty($newPivots)) {
                    GuruPivot::insert($newPivots);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data kelas berhasil diperbarui'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error setClass: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getClass($id)
    {
        Log::debug($id);
        $datas = GuruPivot::with('kelas', 'jurusan', 'guru')->where('guru_id', $id)->get();
        $kelasDatas = [];
        $kelasDataIds = [];

        for ($i = 0; $i <= $datas->count() - 1;) {
            $kelasDatasContainer = $datas[$i]->kelas->name . ' ' . $datas[$i]->jurusan->name;
            $kelasDatas[] = strtoupper($kelasDatasContainer);
            $kelasDataIds[] = $datas[$i]->id;
            $i++;
        }

        return response()->json([
            'success' => true,
            'data' => $kelasDatas,
            'id' => $kelasDataIds
        ]);
    }
}
