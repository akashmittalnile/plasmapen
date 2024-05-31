<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Dev Name :- Dishant Gupta
    public function dashboard()
    {
        try {
            $activeStu = User::where('role', 2)->where('status', 1)->count();
            $inactiveStu = User::where('role', 2)->where('status', 2)->count();
            return view('pages.dashboard')->with(compact('activeStu', 'inactiveStu'));
        } catch (\Exception $e) {
            return errorMsg('Exception => ' . $e->getMessage());
        }
    }
}