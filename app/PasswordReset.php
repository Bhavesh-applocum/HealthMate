<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factory\HasFactory;

class PasswordReset extends Model
{
    public $table = 'password_resets';
    public $timestamps = false;

    protected $primaryKey = 'email';


    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];
}
