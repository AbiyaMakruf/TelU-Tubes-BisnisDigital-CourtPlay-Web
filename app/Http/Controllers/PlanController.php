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
            $plansRaw = config('plans.plans');

            // === Format harga & detail plan ===
            $plans = [];
            foreach ($plansRaw as $key => $plan) {
                $priceIdr = $plan['price_idr'] ?? 0;
                $isOneTime = $plan['is_one_time'] ?? false;
                
                $plans[$key] = array_merge($plan, [
                    'price_idr' => $priceIdr,
                    'price' => 'Rp' . number_format($priceIdr, 0, ',', '.') . ($isOneTime ? '' : ' / month'),
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
        $data = $request->validate(['plan' => 'required|in:free,starter,plus,pro']);
        $user = Auth::user();

        $user->role = $data['plan'];
        $user->save();

        toastr()->success('Plan changed to ' . ucfirst($data['plan']) . '.');
        return redirect()->route('plan');
    }
}
