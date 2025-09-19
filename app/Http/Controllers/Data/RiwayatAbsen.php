<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\RiwayatAbsen as ModelsRiwayatAbsen;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class RiwayatAbsen extends Controller
{
    public function index()
    {
        return view('data.riwayat-absen');
    }

    public function getData()
    {
        $teacherRole = Auth::user()->hasRole('teacher');
        if($teacherRole){
            $teacherData = User::where('id', Auth::user()->id)->with('');
        }
        // dd($teacherData);

        $data = ModelsRiwayatAbsen::with('user.siswa', 'user.siswa.kelas', 'user.siswa.jurusan')->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('user.siswa.name', function($row) {
                if(!$row->user->siswa){
                    return 'N/A';
                }
                return $row->user->siswa->name;
            })
            ->addColumn('action', function($row) {
                if($row->is_late === 'Terlambat'){
                    return '<span class="badge bg-danger">'.$row->is_late.'</span>';
                } else {
                    return '<span class="badge bg-success">'.$row->is_late.'</span>';
                }
            })
            ->editColumn('created_at', function($row) {
                return $row->created_at->format('d M Y');
            })
            ->addColumn('waktu', function($row) {
                return $row->created_at->format('H:i:s');
            })
            ->editColumn('user.siswa.jurusan.name', function($row){
                return strtoupper($row->user->siswa->jurusan->name) ;
            })
            ->make(true);
    }
}
