<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $data = []; // Fetch or prepare your data here

        $userId = Auth::user()->id;
        $userData = User::with('siswa', 'siswa.kelas', 'siswa.jurusan')->where('id', $userId)->first();
        $data = $userData;
        return view('profile.index', compact('data'));
    }
}
