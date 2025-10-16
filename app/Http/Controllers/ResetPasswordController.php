<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Exception;

class ResetPasswordController extends Controller
{
    public function showResetForm($token)
    {
        try {
            return view('auth.reset-password', ['token' => $token]);
        } catch (Exception $e) {
            Log::error('Show reset form failed', ['error' => $e->getMessage()]);
            toastr()->error('Failed to open reset password page.');
            return redirect()->route('login');
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8|confirmed',
            ]);

            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])->save();
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                Log::info('Password reset successful', ['email' => $request->email]);
                toastr()->success('Your password has been reset.');
                return redirect()->route('login');
            }

            Log::warning('Password reset failed', ['email' => $request->email, 'status' => $status]);
            toastr()->error(__($status));
            return back()->withErrors(['email' => [__($status)]])->withInput();
        } catch (ValidationException $e) {
            Log::warning('Validation failed during password reset', ['errors' => $e->errors()]);
            toastr()->error('Validation failed. Please check your input.');
            return back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            Log::error('Unexpected error during password reset', ['error' => $e->getMessage()]);
            toastr()->error('Unexpected error occurred during password reset.');
            return back()->withInput();
        }
    }

    public function showLinkRequestForm()
    {
        try {
            return view('auth.forgot-password');
        } catch (Exception $e) {
            Log::error('Show forgot-password form failed', ['error' => $e->getMessage()]);
            toastr()->error('Failed to open forgot password page.');
            return redirect()->route('login');
        }
    }

    public function sendResetLink(Request $request)
    {
        try {
            $request->validate(['email' => 'required|email']);

            $user = User::where('email', $request->email)->first();

            if (!$user) {
                Log::warning('Send reset link attempted for non-existent email', ['email' => $request->email]);
                toastr()->warning('Email not found.');
                return back()->withErrors(['email' => 'We couldn’t find an account with that email.']);
            }

            $token = Str::random(64);

            DB::table('password_reset_tokens')->updateOrInsert(
                ['email' => $user->email],
                ['token' => $token, 'created_at' => Carbon::now()]
            );

            try {
                Mail::to($user->email)->send(new ResetPasswordMail($user, $token));
            } catch (Exception $e) {
                Log::error('Error sending reset password email', ['user_id' => $user->id, 'error' => $e->getMessage()]);
                toastr()->error('Failed to send reset password email.');
                return back();
            }

            Log::info('Reset password link sent', ['user_id' => $user->id]);
            toastr()->success('A reset password link has been sent to your email.');
            return back();
        } catch (ValidationException $e) {
            Log::warning('Validation failed during send reset link', ['errors' => $e->errors()]);
            toastr()->error('Validation failed. Please check your input.');
            return back()->withErrors($e->errors())->withInput();
        } catch (QueryException $e) {
            Log::error('Database error during send reset link', ['error' => $e->getMessage()]);
            toastr()->error('Database error occurred while generating reset link.');
            return back()->withInput();
        } catch (Exception $e) {
            Log::error('Unexpected error during send reset link', ['error' => $e->getMessage()]);
            toastr()->error('Unexpected error occurred while sending reset link.');
            return back()->withInput();
        }
    }
}
