<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RiwayatAbsen extends Controller
{
    public function index()
    {
        return view('data.riwayat-absen');
    }

    public function getData()
    {
        $data = []; // Ganti dengan logika pengambilan data riwayat absen dari database

        return DataTables::of($data)
            ->addIndexColumn()
            ->make(true);
    }
}
