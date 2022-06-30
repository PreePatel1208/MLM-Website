<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $bonus = Income::where("user_id", $user->id)->where('register_bonus', '!=', null)->sum('register_bonus');
        $Level1_income = Income::where("reference_user_id", $user->id)->sum('level1_bonus');
        $Level2_income = Income::where("reference_user_id", $user->id)->sum('level2_bonus');
        $Level3_income = Income::where("reference_user_id", $user->id)->sum('level3_bonus');
        //    dd(   $Level1_income,$Level2_income,$Level3_income);
        $recentRegister = $user;
        $mlm_users =  [];
      
            $recentRegister = User::where('id', '!=', $user->id)->where('referral_code', $recentRegister->unique_code)->orderBy('id', 'desc')->get()->toArray();
            // dd($mlm_users) ;
            if ($recentRegister) {
                array_push($mlm_users, $recentRegister);
                // dd($mlm_users);
                $i=0;
                foreach ($recentRegister as $index=> $ref_user) {  
                    // dump($ref_user['name']);    
                    $level3_user = User::where('id', '!=', $ref_user['id'])->where('referral_code', $ref_user['unique_code'])->orderBy('id', 'desc')->get()->toArray();
                    // dump($level3_user); 
                  
                    if (count($level3_user)>=1) {
                        foreach ($level3_user as $key =>$ref_user) {   
                            // dump($ref_user);  
                            if(is_array($ref_user))
                            array_push($mlm_users[$i][$key], $ref_user);       
                        }
                        // dd($mlm_users);
                    }
                    $i++;
                }
            } else {
             
            }
      
        dd($mlm_users);
        return view('dashboard', ['level1_income' => $Level1_income, 'level2_income' => $Level2_income, 'level3_income' => $Level3_income, 'bonus' => $bonus, 'mlm_users' => $mlm_users, 'user' => $user]);
    }
}
