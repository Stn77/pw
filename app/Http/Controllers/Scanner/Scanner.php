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
            Log::info('Scan data: ', $data);
            return response()->json([
                'data' => $data,
                'status' => 200,
                'message' => 'Scan successful'
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
