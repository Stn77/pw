<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\Guru as ModelsGuru;
use App\Models\User;
use Illuminate\Http\Request;
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

    public function create(Request $request)
    {
        return view('data.guru.create');
    }

    public function edit($id)
    {
        return view('data.guru.edit', compact('id'));
    }
}
