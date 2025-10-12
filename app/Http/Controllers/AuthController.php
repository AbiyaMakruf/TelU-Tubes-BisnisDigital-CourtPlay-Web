<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\UserWelcomeMail;
use Exception;


class AuthController extends Controller
{
    public function showSignupForm()
    {
        return view('auth.signup');
    }

public function signup(Request $request)
{
    try {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:6',
        ]);

        $user = User::create([
            'first_name'   => $request->first_name,
            'last_name'    => $request->last_name,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'role'         => 'user',
            'login_token'  => bin2hex(random_bytes(32)), // API token
        ]);

        Auth::login($user);

        try {
            Mail::to($user->email)->send(new UserWelcomeMail($user));
        } catch (Exception $e) {
            Log::error('❌ Error sending welcome email: ' . $e->getMessage());
        }

        return redirect()
            ->route('analytics')
            ->with('success', 'Account created successfully!');

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::warning('⚠️ Validation failed during signup: ' . json_encode($e->errors()));
        return back()->withErrors($e->errors())->withInput();

    } catch (\Illuminate\Database\QueryException $e) {
        Log::error('💥 Database error during signup: ' . $e->getMessage());
        return back()->with('error', 'Database error: ' . $e->getMessage());

    } catch (Exception $e) {
        Log::error('🔥 Unexpected signup error: ' . $e->getMessage());
        dd('🔥 Unexpected signup error:', $e->getMessage(), $e->getTraceAsString());
    }
}

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            $user->login_token = bin2hex(random_bytes(32));
            $user->save();



            return redirect()->route('analytics')->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials provided.',
        ]);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'You have been logged out.');
    }
}
