<?php

namespace App\Helpers;

use App\Client;
use App\Job;
use GuzzleHttp\Psr7\Request;

class ApplicationStatusHelper
{
    public static function getJobTypeByName($id){
        $allJobtypes = config('constant.job_type');
        $jobType = '';
        for($i = 1; $i <= count($allJobtypes); $i++){
            if($id == $i){
                $jobType = $allJobtypes[$i];
            }
        };
        return $jobType;
    }

    public static function getJobCategoryByName($id){
        $allJobCategory = config('constant.job_Category');
        $jobCategory = '';
        for($i = 1; $i <= count($allJobCategory); $i++){
            if($id == $i){
                $jobCategory = $allJobCategory[$i];
            }
        };
        return $jobCategory;
    }

    public static function getCandidateCategoryByName($id){
        $allJobCategory = config('constant.job_Category');
        $jobCategory = '';
        for($i = 1; $i <= count($allJobCategory); $i++){
            if($id == $i){
                $jobCategory = $allJobCategory[$i];
            }
        };
        return $jobCategory;
    }

    public static function getJobStatusByName($id){
        $allJobStatus = config('constant.job_status');
        $jobStatus = '';
        for($i = 0; $i <= count($allJobStatus); $i++){
            if($id == $i){
                $jobStatus = $allJobStatus[$i];
            }
        };
        return $jobStatus;
    }

    public static function getApplicationStatusByName($id){
        $allApplicationStatus = config('constant.Application_status');
        $applicationStatus = '';
        for($i = 0; $i <= count($allApplicationStatus); $i++){
            if($id == $i){
                $applicationStatus = $allApplicationStatus[$i];
            }
        };
        return $applicationStatus;
    }
}
