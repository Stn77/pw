<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
                'message' => 'Validation passed',
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
