<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;

class UploadController extends Controller
{
    public function index()
    {
        return view('uploads');
    }

    public function store(Request $request)
    {
        $request->validate([
            'video' => 'required|mimes:mp4,mov,avi|max:51200', // max 50MB
        ]);

        $user = auth()->user();
        $file = $request->file('video');
        $localFilePath = $file->getPathname();
        $originalName = $file->getClientOriginalName();
        $timestamp = time();

        // Nama unik: userID + timestamp + nama file
        $objectName = "uploads/videos/{$user->id}/{$timestamp}_{$originalName}";

        // Konfigurasi bucket & key GCS
        $bucket = 'courtplay-storage';
        $keyFile = storage_path('app/keys/courtplay-gcs-key.json');

        // Upload ke GCS pakai helper (fungsi upload_object)
        $publicUrl = upload_object($bucket, $objectName, $localFilePath, $keyFile);

        // Hapus file lokal setelah upload
        if (file_exists($localFilePath)) {
            @unlink($localFilePath);
        }

        // Simpan metadata ke database
        $video = Video::create([
            'user_id' => $user->id,
            'title' => pathinfo($originalName, PATHINFO_FILENAME), // nama file tanpa ekstensi
            'video_original' => $publicUrl,
            'video_keypoint' => null,
            'video_analytics' => null,
            'status' => 'uploaded', // default status
        ]);

        return back()->with('success', 'Video uploaded successfully!')
                     ->with('url', $publicUrl)
                     ->with('video_id', $video->id);
    }
}
