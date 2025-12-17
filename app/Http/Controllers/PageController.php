<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function guestDashboard()
    {
        return view('guest.dashboard');
    }

    public function dashboard()
    {
        return redirect()->route('analytics');
    }

    public function plan()
    {
        $role = strtolower((string) (auth()->user()->role ?? 'free'));
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
    }


}
