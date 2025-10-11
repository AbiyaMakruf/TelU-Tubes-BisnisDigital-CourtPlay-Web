<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectDetail;
use App\Models\User; // Pastikan ini diimpor jika diperlukan untuk relasi user
use Illuminate\Support\Facades\Mail;
use App\Mail\VideoAnalysisCompleteMail; // Diperlukan untuk testEmail

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

        // Cek Kuota sebelum memproses upload
        if ($projectCount >= self::MAX_UPLOAD_LIMIT) {
             return back()->withErrors(['video' => 'You have reached your maximum video analysis limit (100 projects). Please contact support for an upgrade.'])->withInput();
        }

        // 1. Validasi Input
        $request->validate([
            'video' => 'required|mimes:mp4,mov,avi|max:51200', // Batas 50MB (51200 KB)
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $file = $request->file('video');
        $localFilePath = $file->getPathname();
        $originalName = $file->getClientOriginalName();
        $timestamp = time();

        $objectName = "uploads/videos/{$user->id}/{$timestamp}_{$originalName}";

        // Konfigurasi GCS (Asumsi fungsi upload_object sudah tersedia)
        $bucket = 'courtplay-storage';
        $keyFile = storage_path('app/keys/courtplay-gcs-key.json');

        // 2. Upload ke GCS
        // Fungsi upload_object harus diimplementasikan secara global atau di service
        $publicUrl = upload_object($bucket, $objectName, $localFilePath, $keyFile);

        // Hapus file sementara lokal
        if (file_exists($localFilePath)) {
            @unlink($localFilePath);
        }

        // 3. Buat entri ProjectDetail
        $projectDetail = ProjectDetail::create([
            'description' => $request->input('description'),
            'link_original_video' => $publicUrl,
            // Nilai analisis lainnya menggunakan default
        ]);

        // 4. Buat entri Project
        $project = Project::create([
            'user_id' => $user->id,
            'project_details_id' => $projectDetail->id, // UUID dari ProjectDetail
            'project_name' => $request->input('project_name'),
            'upload_date' => now(),
        ]);

        // TODO: Panggil layanan antrian (Queue Service) di sini untuk memulai AI analysis

        return back()->with('success', 'Video uploaded successfully! Analysis is starting soon.')->with('project_id', $project->id);
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
