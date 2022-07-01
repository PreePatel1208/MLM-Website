<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        //Fetch Earnings
        $bonus = Income::where("user_id", $user->id)->where('register_bonus', '!=', null)->sum('register_bonus');
        $Level1_income = Income::where("reference_user_id", $user->id)->sum('level1_bonus');
        $Level2_income = Income::where("reference_user_id", $user->id)->sum('level2_bonus');
        $Level3_income = Income::where("reference_user_id", $user->id)->sum('level3_bonus');
        $recentRegister = $user;
        $mlm_users =  new Collection();

         //Fetch tree level users
        $recentRegister = User::where('id', '!=', $user->id)->where('referral_code', $recentRegister->unique_code)->orderBy('id', 'desc')->get();
        if ($recentRegister) {
            $mlm_users = $recentRegister;
            $i = 0;
            foreach ($recentRegister as $index => $ref_user) {
                $level3_user = User::where('id', '!=', $ref_user['id'])->where('referral_code', $ref_user['unique_code'])->orderBy('id', 'desc')->get()->toArray();
                $mlm_users[$index]->tree_user = $level3_user;
            }
        } else {
        }
        $mlm_users = $mlm_users->toArray();
        return view('dashboard', ['level1_income' => $Level1_income, 'level2_income' => $Level2_income, 'level3_income' => $Level3_income, 'bonus' => $bonus, 'mlm_users' => $mlm_users, 'user' => $user]);
    }
}
