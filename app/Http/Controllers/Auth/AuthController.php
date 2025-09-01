<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle a login request.
     */
    public function loginSubmit(Request $request)
    {
        // Validate and authenticate the user...
        $credentials = $request->only('username', 'password');
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginData = User::where('username', $credentials['username'])->first();
        if (!$loginData) {
            return redirect()->back()->withErrors(['username' => 'Username does not exist.']);
        }
        if (!password_verify($credentials['password'], $loginData->password)) {
            return redirect()->back()->withErrors(['password' => 'The provided password is incorrect.']);
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'password' => 'Server Error',
        ]);
    }

    /**
     * Log the user out.
     */
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }
}
