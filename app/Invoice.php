<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'client_id',
        'job_id',
        'timesheet_id',
        'amount',
        'status'
    ];

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function job()
    {
        return $this->belongsTo('App\Job');
    }

    public function timesheet()
    {
        return $this->belongsTo('App\Timesheet');
    }

    public function application()
    {
        return $this->belongsTo('App\Application');
    }

    public function candidate()
    {
        return $this->belongsTo('App\Candidate');
    }
}
