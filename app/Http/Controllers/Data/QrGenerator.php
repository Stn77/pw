<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class QrGenerator extends Controller
{
    // Test di controller
    public function generate()
    {
        try {
            $user = Auth::user()->id;

            $qrcode = base64_encode(QrCode::size(300)
                ->format('png')
                ->generate($user));

            return view('profile.my-qr', compact('qrcode'));
        } catch (\Exception $e) {
            Log::error('QR Code Generation Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
