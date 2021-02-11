<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $get_user = Auth::user();
        $user_id = $get_user->id;
        $user = User::find($user_id);
        return view('dashboard', compact('user'));
    }
}
