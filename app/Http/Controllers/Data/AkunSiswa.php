<?php

namespace App\Http\Controllers\Data;

use App\Export\Excel;
use App\Http\Controllers\Controller;
use App\Import\ExcelImport;
use App\Import\Template;
use App\Models\Siswa;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Validation\Validator;
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
        } catch (Validator $e){
            Log::error('error validasi data ' . $e->getMessageBag());

            return response()->json([
                'status' => 422,
                'message' => 'input data harus lengkap',
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

    public function edit($id)
    {
        $siswa = Siswa::with('user')->find($id);

        return view('data.siswa-edit', compact(['siswa', 'id']));
    }

    public function update(Request $request)
    {
        try{

            $siswa = Siswa::with('user')->find($request->idSiswa);

            $userImage = $siswa->user->foto_profile;

            if($request->hasFile('image')){
                if($userImage){
                    $oldImagePath = 'public/profile-images/' . $userImage;
                    if (Storage::exists($oldImagePath)) {
                        Storage::delete($oldImagePath);
                    }
                }

                $imageName = time() . generateRandomString(12) . '.' . $request->image->extension();
                $request->image->storeAs('profile-images', $imageName, 'public');

                $siswa->user->foto_profile = $imageName;
                $siswa->user->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Profile berhasil diperbarui!',
                'url' => asset('storage/profile-images/' . $siswa->user->foto_profile)
            ]);

        }catch(Exception $e){
            Log::error('error update siswa: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'error',
                'detail' => $e->getMessage()
            ]);
        }
    }

    public function getSingleData($id)
    {
        $imageUrl = '';
        $data = Siswa::with('user', 'kelas', 'jurusan')->find($id);
        Log::debug($data);
        if($data->user->foto_rofile){
            $imageUrl = asset('storage/profile-images/' . $data->user->foto_profile);
        }else{
            $imageUrl = asset('img/default-profile.png');
        }

        return response()->json([
            'success' => true,
            'image' => $imageUrl,
            'nisn' => $data->nisn,
            'name' => $data->name,
            'email' => $data->email ?? '-',
            'kelas' => strtoupper($data->kelas->name),
            'jurusan' => strtoupper($data->jurusan->name)
        ]);
    }

    public function delete($id){
        try{
            $data = User::with('siswa')->find($id);
            if($data->user->foto_profile){
                $oldImagePath = 'public/profile-images/' . $data->user->foto_profile;
                if (Storage::exists($oldImagePath)) {
                    Storage::delete($oldImagePath);
                }
            }
            $data->siswa->delete();
            $data->delete();
            return response()->json([
                'success' => true
            ]);
        }catch(Exception $e){
            Log::error($e->getMessage());
            return response()->json([
                'success' => false
            ]);
        }
    }
}
