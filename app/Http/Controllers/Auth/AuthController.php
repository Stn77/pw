<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
// use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        // dd($request->username, $request->password);
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginData = User::where('username', $credentials['username'])->first();
        if (!$loginData) {
            Log::error("User not found: " . $credentials['username']);
            return redirect()->back()->withErrors(['error' => 'Username does not exist.']);
        }
        if (!password_verify($credentials['password'], $loginData->password)) {
            return redirect()->back()->withErrors(['error' => 'The provided password is incorrect.']);
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->redirectRole();
        }

        return back()->withErrors([
            'password' => 'Server Error',
        ]);
    }

    public function redirectRole()
    {
        $authUser = Auth::user()->id;
        $user = User::with('roles')->where('id', $authUser)->first();
        if ($user->hasRole('admin')) {
            // dd('youre a admin');
            return redirect()->route('home');
        } elseif ($user->hasRole('scanner')) {
            // dd('youre a scanner');
            return redirect()->route('scanner');
        } else {
            // dd('youre a user');
            return redirect()->route('profile.index');
        }
    }

    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate(); // Invalidates the current session
        $request->session()->regenerateToken(); // Regenerates the CSRF token for the next session
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
