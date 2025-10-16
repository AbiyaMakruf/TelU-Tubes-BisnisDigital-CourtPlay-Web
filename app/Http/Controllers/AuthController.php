<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserWelcomeMail;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

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
                'username'   => 'required|string|max:50|unique:users',
                'email'      => 'required|string|email|max:255|unique:users',
                'password'   => 'required|string|min:6',
            ]);

            $user = User::create([
                'first_name'  => $request->first_name,
                'last_name'   => $request->last_name,
                'username'    => $request->username,
                'email'       => $request->email,
                'password'    => Hash::make($request->password),
                'role'        => 'user',
                'login_token' => bin2hex(random_bytes(32)),
            ]);

            Auth::login($user);

            try {
                Mail::to($user->email)->send(new UserWelcomeMail($user));
            } catch (Exception $e) {
                Log::error('Error sending welcome email', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            }

            toastr()->success('Account created successfully.');
            return redirect()->route('analytics');
        } catch (ValidationException $e) {
            Log::warning('Validation failed during signup', ['errors' => $e->errors()]);
            toastr()->error('Validation failed. Please check your input.');
            return back()->withErrors($e->errors())->withInput();
        } catch (QueryException $e) {
            Log::error('Database error during signup', ['error' => $e->getMessage()]);
            toastr()->error('Database error occurred during signup.');
            return back()->withInput();
        } catch (Exception $e) {
            Log::error('Unexpected signup error', ['error' => $e->getMessage()]);
            toastr()->error('Unexpected error occurred during signup.');
            return back()->withInput();
        }
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email'    => 'required|string',
                'password' => 'required|string',
            ]);

            $remember = $request->filled('remember');
            $loginField = filter_var($credentials['email'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            if (Auth::attempt([$loginField => $credentials['email'], 'password' => $credentials['password']], $remember)) {
                $request->session()->regenerate();
                $user = Auth::user();
                $user->login_token = bin2hex(random_bytes(32));
                $user->save();

                toastr()->success('Welcome back.', 'Login Success');
                return redirect()->route('analytics');
            }

            toastr()->error('Login failed. Invalid credentials.', 'Login Failed');
            return back()->withErrors([
                'email' => 'Invalid credentials. Please check your ' . ($loginField === 'email' ? 'email' : 'username') . '.',
            ]);
        } catch (ValidationException $e) {
            Log::warning('Validation failed during login', ['errors' => $e->errors()]);
            toastr()->error('Validation failed. Please check your input.');
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            Log::error('Unexpected login error', ['error' => $e->getMessage()]);
            toastr()->error('Unexpected error occurred during login.');
            return back()->withInput();
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            toastr()->success('You have been logged out.');
            return redirect()->route('login');
        } catch (Exception $e) {
            Log::error('Unexpected logout error', ['error' => $e->getMessage()]);
            toastr()->error('Unexpected error occurred during logout.');
            return redirect()->route('login');
        }
    }
}
