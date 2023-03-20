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

    public static function getparkingByName($id){
        $parking = config('constant.parking');
        $parkingStatus = '';
        for($i = 0; $i <= count($parking); $i++){
            if($id == $i){
                $parkingStatus = $parking[$i];
            }
        };
        return $parkingStatus;
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

    public static function getTimesheetStatusByStatus($id){
        $bookingStatus = config('constant.Timesheet_status_client');
        $afterStatus = '';
        for($i = 0; $i <= count($bookingStatus); $i++){
            if($id == $i){
                $afterStatus = $bookingStatus[$i];
            }
        };
        return $afterStatus;
    }

    public static function getAfterWorkingStatusByName($id){
        $allPaymentStatus = config('constant.Working_status_candidate');
        $paymentStatus = '';
        for($i = 0; $i <= count($allPaymentStatus); $i++){
            if($id == $i){
                $paymentStatus = $allPaymentStatus[$i];
            }
        };
        return $paymentStatus;
    }

    public static function getInvoiceStatusByName($id){
        $allInvoiceStatus = config('constant.Invoice_status');
        $invoiceStatus = '';
        for($i = 0; $i <= count($allInvoiceStatus); $i++){
            if($id == $i){
                $invoiceStatus = $allInvoiceStatus[$i];
            }
        };
        return $invoiceStatus;
    }
}
