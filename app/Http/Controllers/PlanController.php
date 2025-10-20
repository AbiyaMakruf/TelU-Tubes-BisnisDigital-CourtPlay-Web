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
            $usdToIdr = config('plans.usd_to_idr');
            $plansRaw = config('plans.plans');

            // === Proses format harga & detail plan ===
            $plans = [];
            foreach ($plansRaw as $key => $plan) {
                $priceUsd = $plan['price_usd'] ?? 0;
                $priceIdr = $priceUsd * $usdToIdr;

                $plans[$key] = array_merge($plan, [
                    'price_idr' => $priceIdr,
                    'price' => $priceUsd > 0
                        ? 'Rp' . number_format($priceIdr, 0, ',', '.') . ' / month'
                        : 'Rp0 / month',
                ]);
            }

            return view('plan', [
                'plans' => $plans,
                'currentRole' => $role,
            ]);

        } catch (Throwable $e) {
            Log::error('Plan page load failed', [
                'user_id' => optional(Auth::user())->id,
                'error' => $e->getMessage()
            ]);
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

        toastr()->success('Plan changed to ' . ucfirst($data['plan']) . '.');
        return redirect()->route('plan');
    }
}
