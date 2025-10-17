<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Throwable;

class PlanController extends Controller
{
    public function plan()
    {
        try {
            $role = strtolower((string) (Auth::user()->role ?? 'free'));

            $plans = [
                'free' => [
                    'name' => 'Free',
                    'price' => '$0/month',
                    'users' => '1 user',
                    'limit' => (int) env('UPLOAD_LIMIT_FREE', 3),
                    'max_mb' => (int) env('UPLOAD_MAX_FILE_MB_FREE', 200),
                    'features' => [
                        'Up to '.env('UPLOAD_LIMIT_FREE', 3).' video analytics',
                        'Dashboard metrics',
                        'AI mapping',
                    ],
                    'tone' => '#e6f9ff',
                ],
                'pro' => [
                    'name' => 'Pro',
                    'price' => '$49/month',
                    'users' => '3 user',
                    'limit' => (int) env('UPLOAD_LIMIT_PRO', 50),
                    'max_mb' => (int) env('UPLOAD_MAX_FILE_MB_PRO', 1024),
                    'features' => [
                        'Up to '.env('UPLOAD_LIMIT_PRO', 50).' video analytics',
                        'Dashboard metrics',
                        'AI mapping',
                        'Priority processing',
                    ],
                    'tone' => '#f2f6ff',
                ],
                'plus' => [
                    'name' => 'Plus',
                    'price' => '$200/month',
                    'users' => '5 user',
                    'limit' => (int) env('UPLOAD_LIMIT_PLUS', 200),
                    'max_mb' => (int) env('UPLOAD_MAX_FILE_MB_PLUS', 2048),
                    'features' => [
                        'Up to '.env('UPLOAD_LIMIT_PLUS', 200).' video analytics',
                        'Dashboard metrics',
                        'AI mapping',
                        'Unlocked new feature',
                        'Custom video analytics',
                        'Unlimited storage',
                    ],
                    'tone' => '#eefcc8',
                ],
            ];

            return view('plan', [
                'plans' => $plans,
                'currentRole' => $role,
            ]);
        } catch (Throwable $e) {
            Log::error('Plan page load failed', ['user_id' => optional(Auth::user())->id, 'error' => $e->getMessage()]);
            toastr()->error('Failed to load plan page.');
            return back();
        }
    }

    public function changePlan(Request $request)
    {
        $data = $request->validate(['plan' => 'required|in:free,pro,plus']);
        $user = Auth::user();
        $user->role = $data['plan'];
        $user->save();
        toastr()->success('Plan changed to '.$data['plan'].'.');
        return redirect()->route('plan');
    }
}

