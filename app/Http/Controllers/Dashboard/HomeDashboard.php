<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\RiwayatAbsen;
// use App\Http\Controllers\Data\RiwayatAbsen;
use Illuminate\Http\Request;

class HomeDashboard extends Controller
{
    //
    public function index(){
        $absenTelat = RiwayatAbsen::whereDate('created_at', date('Y-m-d'))->where('is_late', 'Terlambat')->count();
        $absenTepatWaktu = RiwayatAbsen::whereDate('created_at', date('Y-m-d'))->where('is_late', 'Tepat Waktu')->count();
        // dd($absenTepatWaktu);
        $riwayatAbsen = RiwayatAbsen::whereDate('created_at', date('Y-m-d'))->count();
        return view('dashboard', compact('absenTelat', 'absenTepatWaktu', 'riwayatAbsen'));
    }
}
