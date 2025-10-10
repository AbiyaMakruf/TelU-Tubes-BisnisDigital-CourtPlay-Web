<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserWelcomeMail;


class AuthController extends Controller
{
    // 🔹 Tampilkan halaman signup
    public function showSignupForm()
    {
        return view('auth.signup');
    }

    // 🔹 Proses signup

    public function signup(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname'  => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:6',
        ]);

        $user = User::create([
            'firstname'   => $request->firstname,
            'lastname'    => $request->lastname,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'role'        => 'user',
            'login_token' => bin2hex(random_bytes(32)),
        ]);

        Auth::login($user, false);

        // 🔹 Kirim email selamat datang
        Mail::to($user->email)->send(new UserWelcomeMail($user));

        return redirect()->route('dashboard')->with('success', 'Account created successfully!');
    }


    // 🔹 Tampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // 🔹 Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Cek apakah user memilih remember me
        $remember = $request->filled('remember'); // true jika checkbox dicentang

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // 🔹 Generate token API baru setiap login
            $user->login_token = bin2hex(random_bytes(32)); // token unik 64 karakter
            $user->save();

            return redirect()->route('dashboard')->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials provided.',
        ]);
    }


    // 🔹 Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}
