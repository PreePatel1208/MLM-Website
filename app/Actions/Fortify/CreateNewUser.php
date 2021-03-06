<?php

namespace App\Actions\Fortify;

use App\Models\Income;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;


class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        //function creation of unique code 
        function generateRandomString($length = 25)
        {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'referral_code' => ['nullable', 'min:8', 'exists:users,unique_code'], //validate valid referral code
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();
        $input['unique_code'] = generateRandomString(8);

        if (!$input['referral_code'])
            $input['referral_code'] = null;
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'referral_code' => $input['referral_code'],
            'unique_code' => $input['unique_code'],
        ]);
        if ($user->referral_code) {
            //create register user bonus
            Income::create([
                'user_id' => $user->id,
                'register_bonus' => 100,
            ]);
            $recentRegister = $user;
            $mlm_users = [];
            // retrive users up to level 3 
            for ($ievel = 1; $ievel <= 3; $ievel++) {
                $recentRegister = User::where('id', '!=', $user->id)->where('unique_code', $recentRegister->referral_code)->orderBy('id', 'desc')->first();
                if ($recentRegister) {
                    array_push($mlm_users, $recentRegister);
                } else {
                    break;
                }
            }
            if (count($mlm_users) > 1) {
                $level = 3;
                foreach ($mlm_users as $refence_user) {
                    if ($level == 3) {
                        // create 30% bonus for level 1 user
                        $income = Income::create([
                            'user_id' => $user->id,
                            'reference_user_id' => $refence_user['id'],
                            'level1_bonus' => round((100 * ($level * 10)) / 100),
                        ]);
                    } else if ($level == 2) {
                        // create 20% bonus for level 2 user
                        $income = Income::create([
                            'user_id' => $user->id,
                            'reference_user_id' => $refence_user['id'],
                            'level2_bonus' => round((100 * ($level * 10)) / 100),
                        ]);
                    } else if ($level == 1) {
                        // create 30% bonus for level 3 user
                        $income =  Income::create([
                            'user_id' => $user->id,
                            'reference_user_id' => $refence_user['id'],
                            'level3_bonus' => round((100 * ($level * 10)) / 100),
                        ]);
                    }
                    $level = $level - 1;
                }
            }
        }
        return $user;
    }
}
