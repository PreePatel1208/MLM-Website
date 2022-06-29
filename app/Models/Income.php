<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'reference_user_id',
        'register_bonus',
        'level1_bonus', // add for stronig referral code 
        'level2_bonus',
        'level3_bonus',
    ];
}
