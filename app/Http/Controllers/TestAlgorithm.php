<?php

namespace App\Http\Controllers;

use App\Models\GuruPivot;
use App\Models\User;
use Illuminate\Http\Request;

class TestAlgorithm extends Controller
{
    public function index()
    {
        $this->getKelasJurusanGuru();
    }

    function getTeacher()
    {
        return User::role('teacher')->get();
    }

    function getKelasJurusanGuru()
    {
        $datas = GuruPivot::with('kelas', 'jurusan', 'guru')->where('guru_id', 1)->get();
        $kelasDatas = [];
        $kelasDataIds = [];

        for ($i = 0; $i <= $datas->count()-1;){
            $kelasDatasContainer = $datas[$i]->kelas->name . ' ' . $datas[$i]->jurusan->name;
            $kelasDatas[] = strtoupper($kelasDatasContainer);
            $kelasDataIds[] = $datas[$i]->id;
            $i++;
        }

        dd($kelasDatas, $kelasDataIds);

        return $kelasDatas;
    }

    function getIdKelasJurusanGuru()
    {
        $datas = GuruPivot::where('guru_id', 1)->get();

        dd($datas->kelas_id);
    }
}
