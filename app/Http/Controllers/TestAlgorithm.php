<?php

namespace App\Http\Controllers;

use App\Models\GuruPivot;
use App\Models\RiwayatAbsen;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestAlgorithm extends Controller
{
    public function index()
    {
        $this->getRiwayatAbsen();
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

        for ($i = 0; $i <= $datas->count() - 1;) {
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

    function getRiwayatAbsen()
    {
        // Anggap ini adalah guru yang sedang aktif
        $guruId = Auth::user()->guru->id;

        // Dapatkan ID Kelas dan Jurusan yang diajar oleh guru
        $teacherClasses = GuruPivot::where('guru_id', $guruId)
            ->select('kelas_id', 'jurusan_id')
            ->get();

        // Jika tidak ada data
        if ($teacherClasses->isEmpty()) {
            return collect([]); // Kembalikan koleksi kosong
        }

        // **Cara 1: Menggunakan Where Clause Kompleks (Lebih bersih jika banyak kombinasi)**
        $siswaQuery = Siswa::query();

        // Buat clause OR untuk setiap kombinasi kelas dan jurusan
        $siswaQuery->where(function ($query) use ($teacherClasses) {
            foreach ($teacherClasses as $combination) {
                $query->orWhere(function ($q) use ($combination) {
                    $q->where('kelas_id', $combination->kelas_id)
                        ->where('jurusan_id', $combination->jurusan_id);
                });
            }
        });

        $targetUserIds = $siswaQuery->pluck('user_id')->unique();

        // Ambil Riwayat Absen
        $riwayatAbsen = RiwayatAbsen::whereIn('user_id', $targetUserIds)
            ->latest()
            ->with('user')
            ->get();

    }
}
