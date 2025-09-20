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
        $riwayatAbsen = RiwayatAbsen::whereDate('created_at', date('Y-m-d'));
        $absenTelat = $riwayatAbsen->where('is_late', 'terlambat')->count();
        $absenTepatWaktu = $riwayatAbsen->where('is_late', 'tepat waktu')->count();
        $riwayatAbsen = $riwayatAbsen->count();
        return view('dashboard', compact('absenTelat', 'absenTepatWaktu', 'riwayatAbsen'));
    }
}
