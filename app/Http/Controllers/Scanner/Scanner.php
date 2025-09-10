<?php

namespace App\Http\Controllers\Scanner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Scanner extends Controller
{
    public function index()
    {
        return view('scanner.scanner');
    }

    public function scan(Request $request)
    {
        try {
            $data = $request->all();
            // Log::info('Scan data: ' . json_encode((int)$data['decodedText']));

            date_default_timezone_set('Asia/Jakarta');
            $defaultTime = '07:00:00';
            $currentTime = date('H:i:s');

            if ($currentTime < $defaultTime) {
                $isLate = 'Tepat Waktu';
                $status = 200;
            } else {
                $isLate = 'Terlambat';
                $status = 201;
            }

            $absenHistory = [];

            $isAbsen = \App\Models\RiwayatAbsen::where('user_id', (int) $data['decodedText'])
                ->where('tanggal', date('Y-m-d'))
                ->first();


            if ($isAbsen) {
                return response()->json([
                    'data' => $data,
                    'status' => 400,
                    'waktu' => date('H:i:s'),
                    'messageTest' => 'You have already checked in today',
                    'mainMessage' => 'Anda sudah absen hari ini',
                ]);
            }

            \App\Models\RiwayatAbsen::create([
                'user_id' => (int) $data['decodedText'],
                'tanggal' => date('Y-m-d'),
                'hari' => date('l'),
                'is_late' => $isLate,
            ]);


            return response()->json([
                'data' => $data,
                'status' => $status,
                'waktu' => date('H:i:s'),
                'messageTest' => 'Absen berhasil ' . $isLate . ' - ' . $currentTime . ' - ' . $defaultTime,
                'mainMessage' => 'Absen berhasil ' . $isLate,
            ]);
        } catch (\Exception $e) {
            Log::error('Scan error: ' . $e->getMessage());
            return response()->json([
                'error' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }
}
