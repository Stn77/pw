<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AkunSiswa extends Controller
{
    public function index()
    {
        return view('data.akun-siswa');
    }

    public function getData()
    {
        $data = User::role('user')->with('siswa', 'siswa.kelas', 'siswa.jurusan')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('siswa.jurusan.name', function($row) {
                return strtoupper($row->siswa->jurusan->name);
            })
            ->addColumn('action', function($row) {
                $btn = '<button class="btn mx-2 btn-sm btn-primary">Edit</button>';
                $btn .= '<button class="btn mx-2 btn-sm btn-danger">Delete</button>';
                return $btn;
            })
            ->make(true);
    }

}
