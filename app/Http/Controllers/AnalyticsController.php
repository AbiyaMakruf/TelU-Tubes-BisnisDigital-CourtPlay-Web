<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;


class AnalyticsController extends Controller
{
    // Batas Maksimum Proyek yang Diperbolehkan
    const MAX_UPLOAD_LIMIT = 100;

    // Tambahkan parameter Request untuk menerima input pencarian dan pengurutan
    public function index(Request $request)
    {
        // Pastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        // Ambil parameter dari request
        $searchQuery = $request->get('search', '');
        $sortBy = $request->get('sort', 'newest'); // Default sorting: newest

        // 1. Inisiasi Query Dasar untuk Daftar Proyek
        $query = Project::where('user_id', $user->id)
            ->with('projectDetails');

        // 2. Terapkan Filter Pencarian
        if ($searchQuery) {
            // Menggunakan 'ilike' untuk case-insensitive search di PostgreSQL
            $query->where('project_name', 'ilike', '%' . $searchQuery . '%');
        }

        // 3. Terapkan Pengurutan
        if ($sortBy === 'alphabet') {
            // Urutkan berdasarkan nama proyek (A-Z)
            $query->orderBy('project_name', 'asc');
        } else {
            // Default: newest (terakhir diunggah)
            $query->orderBy('upload_date', 'desc');
        }

        // Ambil data proyek yang sudah difilter dan diurutkan
        $allProjects = $query->get();

        // 4. Hitung Metrik (Selalu berdasarkan SEMUA proyek pengguna untuk statistik)
        $totalProjectsForUser = Project::where('user_id', $user->id)->count();
        $videoDoneCount = Project::where('user_id', $user->id)->where('is_mailed', true)->count();

        // Video In Process: is_mailed = false, TAPI project_details_id sudah terisi.
        $videoInProcessCount = Project::where('user_id', $user->id)
            ->where('is_mailed', false)
            ->whereNotNull('project_details_id')
            ->count();

        // Hitung persentase untuk Gauge Chart
        $percentageUsed = ($totalProjectsForUser / self::MAX_UPLOAD_LIMIT) * 50;

        // 5. Siapkan Data untuk View
        $data = [
            // Metrik Atas
            'maxLimit' => self::MAX_UPLOAD_LIMIT,
            'projectCount' => $totalProjectsForUser,
            'percentageUsed' => min(100, $percentageUsed),
            'videoInProcessCount' => $videoInProcessCount,
            'videoDoneCount' => $videoDoneCount,

            // Proyek (Card Bawah) - Hasil Filter
            'projects' => $allProjects,
            'currentSearch' => $searchQuery,
            'currentSort' => $sortBy,
        ];

        return view('analytics', $data);
    }
}
