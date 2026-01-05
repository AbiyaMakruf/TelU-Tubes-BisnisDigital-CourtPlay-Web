<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Follow;

class PublicProfileController extends Controller
{
    public function show($username)
    {
        // Ambil data user
        $user = User::where('username', $username)->firstOrFail();

        // Cek apakah pengguna sudah login dan apakah sedang mengikuti
        $userId = auth()->check() ? auth()->user()->id : null;
        $isFollowing = null;
        if ($userId) {
            $isFollowing = Follow::isFollowing($userId, $user->id); // Menggunakan method isFollowing yang telah dibuat sebelumnya
        }

        // ===== Ambil total data user =====
        $projects = Project::where('user_id', $user->id)
            ->join('project_details', 'projects.project_details_id', '=', 'project_details.id') // Gabungkan project dengan project_details
            ->select('projects.*', 'project_details.*') // Ambil semua kolom dari projects dan project_details
            ->orderBy('projects.created_at', 'desc') // Tentukan kolom created_at dari tabel projects
            ->take(3) // Hanya mengambil 3 proyek terakhir
            ->get();

        $allprojects = Project::where('user_id', $user->id)
            ->with('projectDetails') // Pastikan relasi 'projectDetails' dimuat
            ->latest() // Mengambil proyek terbaru terlebih dahulu
            ->get();

        $totalProjects = $allprojects->count();

        $totalPlayingTime = $allprojects->sum(function($project) {
            return $project->projectDetails->video_duration ?? 0;
        });

        $playtime = sprintf('%dh %02dm', floor($totalPlayingTime / 3600), floor(($totalPlayingTime % 3600) / 60));



        $totals = [
            'forehand' => 0,
            'backhand' => 0,
            'serve'    => 0,
            'ready'    => 0,
            'duration' => 0,
        ];

        foreach ($projects as $p) {
            $d = $p; // Karena kita sudah join, data project dan project_details sudah ada di objek $p
            if ($d) {
                $totals['forehand'] += $d->forehand_count ?? 0;
                $totals['backhand'] += $d->backhand_count ?? 0;
                $totals['serve']    += $d->serve_count ?? 0;
                $totals['ready']    += $d->ready_position_count ?? 0;
                $totals['duration'] += $d->video_duration ?? 0;

                if ($d->forehand_count > $d->backhand_count) {
                    $d->major_movement = 'Forehand';  // Major movement adalah Forehand jika forehand lebih banyak
                } elseif ($d->backhand_count > $d->forehand_count) {
                    $d->major_movement = 'Backhand';  // Major movement adalah Backhand jika backhand lebih banyak
                } else {
                    $d->major_movement = 'Balanced';  // Jika keduanya sama, set Balanced
                }
            }
        }


        // ===== Grafik 1: Project History (6 Bulan Terakhir) =====
       $sixMonthsAgo = Carbon::now()->subMonths(6);

        $monthlyProjectCounts = collect(range(0, 6))->mapWithKeys(function ($i) use ($user, $sixMonthsAgo) {
            $month = $sixMonthsAgo->copy()->addMonths($i);

            $count = Project::where('user_id', $user->id)
                ->whereYear('upload_date', $month->year)
                ->whereMonth('upload_date', $month->month)
                ->count();

            return [$month->format('M') => $count];
        });

        // ===== Grafik 0: Yearly Trend (jumlah match per bulan tahun ini) =====
        $yearStart = Carbon::now()->startOfYear();
        $yearlyProjects = Project::where('user_id', $user->id)
            ->whereYear('upload_date', Carbon::now()->year)
            ->selectRaw('EXTRACT(MONTH FROM upload_date) as month_num, COUNT(*) as total_matches')
            ->groupBy('month_num')
            ->orderBy('month_num')
            ->get()
            ->keyBy('month_num');

        // Buat array lengkap 12 bulan
        $monthLabels = [];
        $monthlyMatches = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthLabels[] = Carbon::create()->month($i)->format('M');
            $monthlyMatches[] = (int)($yearlyProjects[$i]->total_matches ?? 0);
        }

        // ===== Grafik 2: Stroke Trend (3 bulan terakhir) =====
        $startMonth = Carbon::now()->subMonths(2)->startOfMonth(); // 3 bulan: sekarang + 2 kebelakang

        $strokeMonthly = Project::where('user_id', $user->id)
            ->join('project_details', 'projects.project_details_id', '=', 'project_details.id')
            ->whereDate('upload_date', '>=', $startMonth)
            ->selectRaw('TO_CHAR(upload_date, \'YYYY-MM\') as month_key,
                        SUM(project_details.forehand_count) as forehand,
                        SUM(project_details.backhand_count) as backhand,
                        SUM(project_details.serve_count) as serve,
                        SUM(project_details.ready_position_count) as ready')
            ->groupBy('month_key')
            ->orderBy('month_key')
            ->get()
            ->keyBy('month_key'); // biar gampang isi nol nanti

        // Buat list 3 bulan terakhir secara eksplisit
        $months = collect([
            Carbon::now()->subMonths(2),
            Carbon::now()->subMonths(1),
            Carbon::now(),
        ]);

        $labels = [];
        $forehandData = [];
        $backhandData = [];
        $serveData = [];
        $readyData = [];

        foreach ($months as $m) {
            $key = $m->format('Y-m');
            $labels[] = $m->format('M');
            $forehandData[] = (int)($strokeMonthly[$key]->forehand ?? 0);
            $backhandData[] = (int)($strokeMonthly[$key]->backhand ?? 0);
            $serveData[] = (int)($strokeMonthly[$key]->serve ?? 0);
            $readyData[] = (int)($strokeMonthly[$key]->ready ?? 0);
        }

        // ===== Avatar & nama =====
        $photoUrl = $user->profile_picture_url;
        $initials = strtoupper(substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1));

        function formatCount($num) {
            if ($num >= 1000000) {
                return round($num / 1000000, 1) . 'M';
            } elseif ($num >= 10000) {
                return round($num / 1000, 1) . 'K';
            } elseif ($num >= 1000) {
                return number_format($num);
            }
            return $num;
        }


        // ===== Get followers and following counts =====
        $followData = Follow::where('user_id', $user->id)->first();

        $followersCountRaw = $followData->followers_count ?? 0;
        $followingCountRaw = $followData->following_count ?? 0;

        $followersCount = formatCount($followersCountRaw);
        $followingCount = formatCount($followingCountRaw);

        // dd($projects);

        return view('public-profile', [
            'user' => $user,
            'photoUrl' => $photoUrl,
            'initials' => $initials,
            'monthlyProjectCounts' => $monthlyProjectCounts,
            'totalProjects' => $totalProjects,
            'strokeStats' => $totals,
            'playtime' => $playtime,
            'labels' => $labels,
            'forehandData' => $forehandData,
            'backhandData' => $backhandData,
            'serveData' => $serveData,
            'readyData' => $readyData,
            'monthLabels' => $monthLabels,
            'monthlyMatches' => $monthlyMatches,
            'projects' => $projects, // Menambahkan projek ke view
            'userId' => $userId,
            'isFollowing' => $isFollowing,
            'followersCount' => $followersCount,
            'followingCount' => $followingCount,
        ]);
    }
}

