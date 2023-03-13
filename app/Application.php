<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    public function job(){
        return $this->belongsToMany('App\Job');
    }

    public function candidate(){
        return $this->belongsToMany('App\Candidate');
    }

    public function timesheet(){
        return $this->belongsToMany('App\Timesheet');
    }
}
