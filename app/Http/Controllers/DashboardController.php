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
        //get user data
        $get_user = Auth::user();
        $payee = $get_user->id;
        $user = User::find($payee);

        //get all users
        $users = User::all();
        
        //TODO - verificar utilidade
        $count = Wallet::where('money', '>', 0)->count();

        return view('dashboard', compact('user', 'users', 'count'));
    }
}
