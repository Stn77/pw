<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Import\ExcelImport;
use App\Models\Guru as ModelsGuru;
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


    public function create(Request $request)
    {
        return view('data.guru.create');
    }

    public function edit($id)
    {
        return view('data.guru-edit', compact('id'));
    }
}
