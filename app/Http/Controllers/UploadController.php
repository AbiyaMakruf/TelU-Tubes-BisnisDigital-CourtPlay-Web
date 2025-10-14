<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectDetail;
use App\Models\Hwinfo;
use App\Models\User; // Pastikan ini diimpor jika diperlukan untuk relasi user
use Illuminate\Support\Facades\Mail;
use App\Mail\VideoAnalysisCompleteMail; // Diperlukan untuk testEmail
use Illuminate\Support\Facades\Log;



class UploadController extends Controller
{
    // Batas maksimum upload
    const MAX_UPLOAD_LIMIT = 100;

    /**
     * Tampilkan halaman upload video dan hitung sisa kuota.
     */
    public function index()
    {
        $user = auth()->user();

        // 1. Hitung jumlah proyek yang sudah diunggah oleh pengguna ini
        // Karena ID adalah UUID, kita menggunakan 'id' pada Model User
        $projectCount = $user->projects()->count();

        // 2. Hitung sisa kuota
        $remainingQuota = self::MAX_UPLOAD_LIMIT - $projectCount;

        return view('uploads', [
            'projectCount' => $projectCount,
            'maxLimit' => self::MAX_UPLOAD_LIMIT,
            'remainingQuota' => $remainingQuota,
            'hasReachedLimit' => $remainingQuota <= 0,
        ]);
    }

    /**
     * Simpan video dan metadata ke GCS dan database.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $projectCount = $user->projects()->count();

        // === 1. Cek Kuota ===
        if ($projectCount >= self::MAX_UPLOAD_LIMIT) {
            return back()->withErrors([
                'video' => 'You have reached your maximum video analysis limit (100 projects). Please contact support for an upgrade.'
            ])->withInput();
        }

        // === 2. Validasi Input ===
        $request->validate([
            'video' => 'required|mimes:mp4,mov,avi|max:51200', // 50MB
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // === 3. Upload ke GCS ===
        $file = $request->file('video');
        $localFilePath = $file->getPathname();
        $originalName = $file->getClientOriginalName();
        $timestamp = time();

        $objectName = "uploads/videos/{$user->id}/{$timestamp}_{$originalName}";
        $bucket = 'courtplay-storage';
        $keyFile = storage_path('app/keys/courtplay-gcs-key.json');

        $publicUrl = upload_object($bucket, $objectName, $localFilePath, $keyFile);

        // Hapus file sementara lokal
        if (file_exists($localFilePath)) {
            @unlink($localFilePath);
        }

        // === 4. Simpan ke Database ===
        $projectDetail = ProjectDetail::create([
            'description' => $request->input('description'),
            'link_video_original' => $publicUrl,
        ]);

        $project = Project::create([
            'user_id' => $user->id,
            'project_details_id' => $projectDetail->id,
            'project_name' => $request->input('project_name'),
            'upload_date' => now(),
        ]);

        Hwinfo::create([
            'user_id'    => $user->id,
            'project_id' => $project->id,
            'is_success' => false,
        ]);



        // === 5. Kirim ke GPU Service (tanpa menunggu respons) ===
        $url = 'https://courtplay-api-gpu-345589430849.us-central1.run.app/infer/';
        $data = [
            'user_id' => $user->id,
            'project_id' => $project->id,
            'project_details_id' => $projectDetail->id,
        ];

        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 1);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_exec($ch);
            curl_close($ch);
        } catch (\Exception $e) {
            Log::error('Failed to trigger GPU inference: ' . $e->getMessage());
        }

        // === 6. Response ke User ===
        return back()
            ->with('success', 'Video uploaded successfully! Analysis is starting soon.')
            ->with('project_id', $project->id);
    }


    /**
     * Fungsi untuk menguji pengiriman email notifikasi analisis selesai.
     */
    public function testEmail($id)
    {
        $project = Project::with(['user', 'projectDetails'])->find($id);

        if (!$project) {
            return response()->json(['error' => 'Project not found.'], 404);
        }

        if (!$project->projectDetails) {
            return response()->json(['error' => 'Project Details not found for this project. Ensure you created it correctly.'], 404);
        }

        try {
            Mail::to($project->user->email)->send(new VideoAnalysisCompleteMail($project));
            return response()->json(['message' => 'Test email sent successfully to ' . $project->user->email, 'project_name' => $project->project_name]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send email: ' . $e->getMessage()], 500);
        }
    }
}
