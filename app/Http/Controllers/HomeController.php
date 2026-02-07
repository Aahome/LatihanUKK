<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tool;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function homeIndex()
    {
        return view('home', [
            'users' => User::all()
        ]);
    }

    public function adminDashboard()
    {
        return view('admin.dashboard');
    }

    public function staffDashboard()
    {
        return view('staff.dashboard');
    }
}