<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'ref_no',
        'job_title',
        'client_id',
        'job_description',
        'job_status',
        'job_type',
        'job_category',
        'job_location',
        'job_start_date',
        'job_end_date',
        'job_start_time',
        'job_end_time',
        'job_salary',
        'break_time',
        'admin_time'
    ];
    public function client(){
        return $this->belongsTo('App\Client');
    }
}
