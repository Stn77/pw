<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\Guru as ModelsGuru;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class Guru extends Controller
{

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
        try{
            $template = new \App\Import\Template();

            $template->getTemplateGuru();

            return response()->json([
                'response' => 200,
                'message' => 'Template berhasil diunduh',
            ]);
        }catch(\Exception $e){
            Log::error('Error downloading template: ' . $e->getMessage());
            return response()->json([
                'response' => 500,
                'message' => 'Terjadi kesalahan saat mengunduh template: ' . $e->getMessage(),
            ]);
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
