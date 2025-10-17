<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Throwable;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use App\Models\User;




class ProfileController extends Controller
{

    public function profile()
    {
        try {
            $user = Auth::user();
            if (!$user) return redirect()->route('login');

            // Inisial sederhana; view yang atur fallback visual
            $fn = (string) ($user->first_name ?? '');
            $ln = (string) ($user->last_name ?? '');
            $initials = mb_strtoupper(mb_substr($fn, 0, 1) . mb_substr($ln, 0, 1));
            if ($initials === '') $initials = 'U';

            // Public profile URL
            $baseUrl   = rtrim(config('app.url') ?: url('/'), '/');
            $slug      = $user->username ?: Str::slug(trim($fn.' '.$ln)) ?: 'user';
            $publicUrl = "{$baseUrl}/profiles/{$slug}";

            // Lempar apa adanya; view yang memutuskan tampilkan foto atau inisial
            $photoUrl = $user->profile_picture_url;

            return view('profile', compact('user', 'initials', 'publicUrl', 'photoUrl'));
        } catch (Throwable $e) {
            Log::error('Profile page load failed', [
                'user_id' => optional(Auth::user())->id,
                'error'   => $e->getMessage(),
            ]);
            return back()->with('error', 'Failed to load profile.');
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = Auth::user();

            $request->validate([
                'first_name' => ['required','string','max:255'],
                'last_name'  => ['required','string','max:255'],
                'username'   => ['required','string','max:50', Rule::unique('users','username')->ignore($user->id)],
                'email'      => ['required','email','max:255', Rule::unique('users','email')->ignore($user->id)],
            ]);

            $user->first_name = $request->first_name;
            $user->last_name  = $request->last_name;
            $user->username   = $request->username;
            $user->email      = $request->email;
            $user->save();

            Log::info('Profile updated', ['user_id' => $user->id]);
            toastr()->success('Profile updated.');
            return redirect()->route('profile');
        } catch (ValidationException $e) {
            Log::warning('Profile update validation failed', [
                'user_id' => optional(Auth::user())->id,
                'errors'  => $e->errors()
            ]);
            toastr()->error('Validation failed.');
            return back()->withErrors($e->errors())->withInput();
        } catch (Throwable $e) {
            Log::error('Profile update failed', [
                'user_id' => optional(Auth::user())->id,
                'error'   => $e->getMessage()
            ]);
            toastr()->error('Failed to update profile.');
            return back()->withInput();
        }
    }

    public function updateProfilePicture(Request $request)
    {
        try {
            $user  = Auth::user();
            $maxMb = (int) env('PROFILE_MAX_IMAGE_MB', 2);
            $mimes = env('PROFILE_ALLOWED_MIMES', 'jpg,jpeg,png,webp');

            $request->validate([
                'avatar' => ["required","image","mimes:$mimes","max:".($maxMb*1024)],
            ], [
                'avatar.max'   => "Image may not be greater than {$maxMb} MB.",
                'avatar.mimes' => "Image must be a file of type: {$mimes}.",
            ]);

            $file          = $request->file('avatar');
            $localFilePath = $file->getPathname();
            $ext           = strtolower($file->getClientOriginalExtension());
            $originalName  = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeName      = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);

            // ==== samakan dengan video: gunakan prefix uploads/profile_pictures/... ====
            $bucket  = env('GCS_BUCKET', 'courtplay-storage');
            $keyPath = env('GCS_KEY_PATH', 'storage/app/keys/courtplay-gcs-key.json');
            $keyFile = base_path($keyPath);
            $gac     = env('GOOGLE_APPLICATION_CREDENTIALS', $keyPath);

            $folder  = "uploads/profile_pictures/{$user->id}/";
            $filename = $safeName . '-' . time() . '.' . $ext;
            $object   = $folder . $filename;   // <- ini yang berubah

            if (!getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
                putenv("GOOGLE_APPLICATION_CREDENTIALS={$gac}");
            }

            $publicUrl = upload_object($bucket, $object, $localFilePath, $keyFile);

            if (is_string($localFilePath) && file_exists($localFilePath)) {
                @unlink($localFilePath);
            }

            $user->profile_picture_url = $publicUrl;
            $user->save();

            Log::info('Profile picture updated to GCS', [
                'user_id' => $user->id, 'bucket' => $bucket, 'object' => $object
            ]);

            toastr()->success('Profile picture updated.');
            return redirect()->route('profile');
        } catch (ValidationException $e) {
            Log::warning('Profile picture validation failed', [
                'user_id' => optional(Auth::user())->id,
                'errors'  => $e->errors()
            ]);
            toastr()->error('Image validation failed.');
            return back()->withErrors($e->errors());
        } catch (Throwable $e) {
            try {
                if (isset($localFilePath) && is_string($localFilePath) && file_exists($localFilePath)) {
                    @unlink($localFilePath);
                }
            } catch (Throwable $t) {}
            Log::error('Profile picture update failed', [
                'user_id' => optional(Auth::user())->id,
                'error'   => $e->getMessage()
            ]);
            toastr()->error('Failed to update profile picture.');
            return back();
        }
    }

    public function deleteProfilePicture()
    {
        try {
            $user = auth()->user();
            if (!$user) return redirect()->route('login');

            $url = (string) ($user->profile_picture_url ?? '');
            if ($url !== '') {
                // cukup panggil helper yang baru
                delete_object($url);
            }

            User::whereKey($user->id)->update(['profile_picture_url' => null]);

            toastr()->success('Profile picture removed.');
            return redirect()->route('profile');
        } catch (Throwable $e) {
            Log::error('Delete profile picture failed', [
                'user_id' => optional(auth()->user())->id,
                'error'   => $e->getMessage(),
            ]);
            toastr()->error('Failed to remove profile picture.');
            return back();
        }
    }



}
