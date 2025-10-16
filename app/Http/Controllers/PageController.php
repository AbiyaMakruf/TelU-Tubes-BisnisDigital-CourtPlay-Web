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
        return view('plan');
    }


}
