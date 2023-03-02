<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'avatar',
        'phone',
        'password',
        'role',
        'gender',   
        'forgot_password_otp',
        'forgot_password_otp_expire'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',
        'otp_expire',
        'forgot_password_otp',
        'forgot_password_otp_expire'
    ];

    //protected $primaryKey = 'email';
    public function avatar(){
        
    }
}
