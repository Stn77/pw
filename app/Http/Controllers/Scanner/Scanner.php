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
        try{
            $data = $request->all();
            // Log::info('Scan data: ' . json_encode((int)$data['decodedText']));

            $absenHistory = [
                'user_id' => json_encode((int)$data['decodedText']),
                'tanggal' => date('Y-m-d'),
                'hari' => date('l'),
                'is_late' => 'Tepat Waktu',
            ];

            $isAbsen = \App\Models\RiwayatAbsen::where('user_id', json_encode((int)$data['decodedText']))
                ->where('tanggal', date('Y-m-d'))->first();

            if ($isAbsen) {
                return response()->json([
                    'data' => $data,
                    'status' => 400,
                    'message' => 'You have already checked in today'
                ]);
            }

            \App\Models\RiwayatAbsen::create($absenHistory);

            return response()->json([
                'data' => $data,
                'status' => 200,
                'message' => 'Absen berhasil'
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
