<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PublicProfileController extends Controller
{
    public function show($username)
    {
        $user = User::where('username', $username)->firstOrFail();

        // ===== Ambil total data user =====
        $projects = Project::where('user_id', $user->id)
            ->with('projectDetails')
            ->get();

        $totals = [
            'forehand' => 0,
            'backhand' => 0,
            'serve'    => 0,
            'ready'    => 0,
            'duration' => 0,
        ];

        foreach ($projects as $p) {
            $d = $p->projectDetails;
            if ($d) {
                $totals['forehand'] += $d->forehand_count ?? 0;
                $totals['backhand'] += $d->backhand_count ?? 0;
                $totals['serve']    += $d->serve_count ?? 0;
                $totals['ready']    += $d->ready_position_count ?? 0;
                $totals['duration'] += $d->video_duration ?? 0;
            }
        }

        $playtime = sprintf('%dh %02dm', floor($totals['duration']/3600), floor(($totals['duration']%3600)/60));

        // ===== Grafik 1: Weekly Matches =====
        $sevenDays = collect(range(0, 6))->map(fn($i) => Carbon::now()->subDays(6 - $i)->startOfDay());
        $weeklyMatches = $sevenDays->mapWithKeys(function ($day) use ($user) {
            $count = Project::where('user_id', $user->id)
                ->whereDate('upload_date', $day)
                ->count();
            return [$day->format('D') => $count];
        });

        // ===== Grafik 0: Yearly Trend (jumlah match per bulan tahun ini)
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

        // ===== Grafik 2: Stroke Trend (3 bulan terakhir)
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

        return view('public-profile', [
            'user' => $user,
            'photoUrl' => $photoUrl,
            'initials' => $initials,
            'weeklyMatches' => $weeklyMatches,
            'strokeStats' => $totals,
            'playtime' => $playtime,
            'labels' => $labels,
            'forehandData' => $forehandData,
            'backhandData' => $backhandData,
            'serveData' => $serveData,
            'readyData' => $readyData,
            'monthLabels' => $monthLabels,
            'monthlyMatches' => $monthlyMatches,
        ]);
    }
}
