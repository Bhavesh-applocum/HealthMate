<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeSheet extends Model
{
    public function job(){
        return $this->belongsToMany('App\Job');
    }

    public function candidate(){
        return $this->belongsToMany('App\Candidate');
    }

    public function application(){
        return $this->belongsToMany('App\Application');
    }

    public function client(){
        return $this->belongsToMany('App\Client');
    }
}
