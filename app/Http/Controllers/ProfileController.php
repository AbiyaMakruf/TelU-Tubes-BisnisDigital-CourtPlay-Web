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

            // Inisial sederhana
            $fn = (string) ($user->first_name ?? '');
            $ln = (string) ($user->last_name ?? '');
            $initials = mb_strtoupper(mb_substr($fn, 0, 1) . mb_substr($ln, 0, 1));
            if ($initials === '') $initials = 'U';

            // Public profile URL
            $baseUrl   = 'https://courtplay.my.id';
            $slug      = $user->username ?: Str::slug(trim($fn . ' ' . $ln)) ?: 'user';
            $publicUrl = "{$baseUrl}/{$slug}";

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
                'first_name' => ['required', 'string', 'max:255'],
                'last_name'  => ['required', 'string', 'max:255'],
                'username'   => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
                'email'      => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            ]);

            $user->update([
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'username'   => $request->username,
                'email'      => $request->email,
            ]);

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

            // Ambil konfigurasi dari config/files.php
            $maxMb = (int) config('files.profile.max_image_mb', 2);
            $mimes = implode(',', config('files.profile.allowed_mimes', ['jpg', 'jpeg', 'png', 'webp']));
            $disk  = config('files.profile.storage_disk', 'public');

            $request->validate([
                'avatar' => ["required", "image", "mimes:$mimes", "max:" . ($maxMb * 1024)],
            ], [
                'avatar.max'   => "Image may not be greater than {$maxMb} MB.",
                'avatar.mimes' => "Image must be a file of type: {$mimes}.",
            ]);

            $file          = $request->file('avatar');
            $localFilePath = $file->getPathname();
            $ext           = strtolower($file->getClientOriginalExtension());
            $originalName  = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeName      = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);

            // Struktur penyimpanan sama seperti video
            $bucket  = env('GCS_BUCKET', 'courtplay-storage');
            $keyPath = env('GCS_KEY_PATH', 'storage/app/keys/courtplay-gcs-key.json');
            $keyFile = base_path($keyPath);
            $gac     = env('GOOGLE_APPLICATION_CREDENTIALS', $keyPath);

            $folder   = "uploads/profile_pictures/{$user->id}/";
            $filename = $safeName . '-' . time() . '.' . $ext;
            $object   = $folder . $filename;

            if (!getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
                putenv("GOOGLE_APPLICATION_CREDENTIALS={$gac}");
            }

            // Upload ke GCS via helper
            $publicUrl = upload_object($bucket, $object, $localFilePath, $keyFile);

            if (is_string($localFilePath) && file_exists($localFilePath)) {
                @unlink($localFilePath);
            }

            $user->update(['profile_picture_url' => $publicUrl]);

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
            } catch (Throwable $t) {
                // ignore unlink failure
            }

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
                delete_object($url);
            }

            $user->update(['profile_picture_url' => null]);

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
