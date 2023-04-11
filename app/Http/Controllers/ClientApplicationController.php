<?php

namespace App\Http\Controllers;

use App\Application;
use App\Candidate;
use App\Client;
use App\Helpers\ApplicationStatusHelper;
use App\Helpers\CandidateHelper;
use App\Helpers\CustomPaginationHelper;
use App\Helpers\JobHelper;
use App\Http\Requests\ApproveRequest;
use App\Invoice;
use App\Job;
use App\Timesheet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\New_;

class ClientApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function statusforclient(Request $request)
    {
        $client = Client::with('jobs', 'address')->find($request->id);
        $status = $request->status;
        $jobs = Job::with('applications', 'client')->where('client_id', $client->id)->where('clientJobWorkingStatus', $status);
        // dd($jobs->first());
        $paginateData = CustomPaginationHelper::paginate_data($jobs, request()->query('page') ?? 1);
        $data = [];
        if ($status == 1) {
            foreach ($paginateData['data'] as $key => $job) {
                $dataObj = [];
                // dd($job->applications[0]->candidate_id);
                // dd($job);
                $applicationsIDS = JobHelper::getApplicationIDsfromJob($job);
                // dd($applicationsIDS);
                $candidatesForJob = JobHelper::getCandidateDataForJob($job);
                $candidateObj = [];
                foreach ($candidatesForJob as $key => $can) {
                    $canData = CandidateHelper::getCandidateField($can['id'], ['avatar']);
                    $candidateObj[$key]['candidate_id'] = $can['id'];
                    $candidateObj[$key]['application_id'] = $can['application_id'];
                    $candidateObj[$key]['avatar'] = $canData['avatar'] ?? '';
                }
                // $candidate = Candidate::with('applications')->where('id', $job->applications->candidate_id)->get();
                // dd($candidate);
                $dataObj['id']                  = $job->id;
                $dataObj['candidates']          = array_slice($candidateObj, 0, 3);
                $dataObj['job_title']           = $job->job_title;
                $dataObj['job_category']        = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
                $dataObj['job_location']        = ApplicationStatusHelper::getOnlyArea($job->address_id);
                $dataObj['job_salary']          = $job->job_salary;
                $dataObj['job_date']            = Carbon::createFromFormat('Y-m-d', $job->job_date)->format('d-m-Y');
                $dataObj['job_end_time']        = Carbon::createFromFormat('H:i:s', $job->job_start_time)->format('H:i A');
                $dataObj['job_start_time']      = Carbon::createFromFormat('H:i:s', $job->job_end_time)->format('H:i A');
                $dataObj['extra_count']         = count($job->applications) > 3 ? count($job->applications) - 3 : 0;
                $dataObj['total_applications']  = count($job->applications);
                array_push($data, $dataObj);
            }
        } else if ($status == 2) {
            foreach ($paginateData['data'] as $key => $job) {
                $dataObj = [];
                $all_applications = $job->applications;
                $candidateID = [];
                foreach ($all_applications as $IDForCan) {
                    $candidateID[] = $IDForCan->candidate_id;
                }
                $application = Application::with('job', 'candidate', 'timesheets')
                    ->where(['job_id' => $job->id])->whereIn('status',[2,3])
                    ->first();

                // dd($application);
                // $timesheet = $application->timesheet_id;
                //dd($application->toArray());

                $TMSstatus = ($application != "" && isset($application->timesheets)) ? $application->timesheets->status : "";

                $candidateObj = [];
                // dd($candidateID);
                if ($application) {
                    $can = JobHelper::getCandidateForBookedJobByApplicationID($application->id);

                    $canData = CandidateHelper::getCandidateField($can['id'], ['avatar']);
                    $candidateObj['candidate_id'] = $can['id'];
                    $candidateObj['application_id'] = $can['application_id'];
                    $candidateObj['avatar'] = $canData['avatar'] ?? '';

                    $dataObj['id']                  = $job->id;
                    $dataObj['candidate']           = $candidateObj;
                    $dataObj['job_title']           = $job->job_title;
                    $dataObj['job_category']        = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
                    $dataObj['job_location']        = ApplicationStatusHelper::getOnlyArea($job->address_id);
                    $dataObj['Job_status']          = ApplicationStatusHelper::getTimesheetStatusByStatus($TMSstatus);
                    $dataObj['job_salary']          = $job->job_salary;
                    $dataObj['job_date']            = Carbon::createFromFormat('Y-m-d', $job->job_date)->format('d-m-Y');
                    $dataObj['job_end_time']        = Carbon::createFromFormat('H:i:s', $job->job_start_time)->format('H:i A');
                    $dataObj['job_start_time']      = Carbon::createFromFormat('H:i:s', $job->job_end_time)->format('H:i A');

                    // $timesheet = Application::with('timesheets')->first();
                    // dd($timesheet);
                    // foreach($application as $checkTime){
                    //     $checkApplication = $checkTime->timesheet_id;
                    // }
                    // dd($checkApplication);
                    // foreach($checkApplication as $time){
                    //     $dataObj['timesheet_id'] = $time->timesheet_id;
                    // }
                    // dd($dataObj);
                    // if($checkApplication == null){
                    //     $dataObj['Job_status']          = ApplicationStatusHelper::getApplicationStatusByName($application->status);
                    // }
                    // else{
                    //     $dataObj['Job_status']          = ApplicationStatusHelper::getTimesheetStatusByStatus($application->status);
                    // }
                    array_push($data, $dataObj);
                }
            }
        } else if ($status == 3) {
            foreach ($paginateData['data'] as $key => $job) {
                $dataObj = [];
                $all_applications = $job->applications;
                $candidateID = [];
                foreach ($all_applications as $IDForCan) {
                    $candidateID[] = $IDForCan->candidate_id;
                }
                $application = Application::with('job', 'candidate', 'timesheets', 'invoices')
                    ->where(['job_id' => $job->id, 'status' => 3])
                    ->first();
                // dd($application);
                // $timesheet = $application->timesheet_id;
                //dd($application->toArray());
                
                $Invoice = ($application != "" && isset($application->invoices)) ? $application->invoices->invoice_status : "";

                // dd($candidateID);
                if ($application) {
                    $can = JobHelper::getCandidateForBookedJobByApplicationID($application->id);
                    $candidateObj = [];
                    $canData = CandidateHelper::getCandidateField($can['id'], ['avatar']);
                    $candidateObj['candidate_id'] = $can['id'];
                    $candidateObj['application_id'] = $can['application_id'];
                    $candidateObj['avatar'] = $canData['avatar'] ?? '';
                    $dataObj['id']                  = $job->id;
                    $dataObj['candidate']           = $candidateObj;
                    $dataObj['job_title']           = $job->job_title;
                    $dataObj['job_category']        = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
                    $dataObj['job_location']        = ApplicationStatusHelper::getOnlyArea($job->address_id);
                    $dataObj['Job_status']          = ApplicationStatusHelper::getInvoiceStatusByName($Invoice);
                    $dataObj['job_salary']          = $job->job_salary;
                    $dataObj['job_date']            = Carbon::createFromFormat('Y-m-d', $job->job_date)->format('d-m-Y');
                    $dataObj['job_end_time']        = Carbon::createFromFormat('H:i:s', $job->job_start_time)->format('H:i A');
                    $dataObj['job_start_time']      = Carbon::createFromFormat('H:i:s', $job->job_end_time)->format('H:i A');
                    $dataObj['invoice_id']          = $application->invoices->id;
                }
                array_push($data, $dataObj);
            }
        }
        // dd($data);
        return response()->json([
            'message' => 'Perfect',
            'sucess' => true,
            'data' => $data,
            'curent_page' => $paginateData['current_page'],
            'last_page' => $paginateData['last_page'],
            'is_last_page' => $paginateData['is_last_page'],
        ], 200);
    }



    //     $job = Job::with('applications')->with('client')->get();
    //     $jobcheckApplication = Client::with('jobs')->find($id);
    //     $jobcheckApplication1 = $jobcheckApplication->jobs;
    //     // dd($jobcheckApplication1);
    //     // dd($job);
    //     $StatusType = $request->statustype;

    //     if($StatusType == 1){
    //     $status = $request->status;
    //     }else{
    //         $timesheetstatus = $request->timesheet_status;
    //     }

    //     $data = [];
    //     if($StatusType == 1){

    //     if ($status == 1) {
    //         foreach ($jobcheckApplication1 as $key => $jobs) {
    //             // dd($jobs);
    //             $checkStatus = $jobs->applications->where('status', 1)->first();
    //             $isBooked =  false;
    //             foreach ($jobs->applications as $application) {
    //                 if ($application->status == 2) {
    //                     $isBooked = true;
    //                 }
    //             }
    //             $isWorked =  false;
    //             foreach ($jobs->applications as $application) {
    //                 if ($application->status == 3) {
    //                     $isWorked = true;
    //                 }
    //             }
    //             // dd($checkStatus);
    //             if ($checkStatus && !$isBooked && !$isWorked) {
    //                 $data[$key]['job_id']           = $jobs->id;
    //                 $data[$key]['job_title']        = $jobs->job_title;
    //                 $data[$key]['job_location']     = $jobs->job_location;
    //                 $data[$key]['job_salary']       = $jobs->job_salary;
    //                 $data[$key]['job_start_date']   = $jobs->job_start_date;
    //                 $data[$key]['job_end_date']     = $jobs->job_end_date;
    //                 $data[$key]['total_applications']   = count($jobs->applications);
    //             }
    //         }
    //         return response()->json([
    //             'message' => 'Applications for client',
    //             'status' => 'OK',
    //             'code' => 200,
    //             'data' => $data
    //         ], 200);
    //     } elseif ($status == 2) {
    //         foreach ($jobcheckApplication1 as $key => $jobs) {
    //             // dd($jobs);
    //             $checkStatus = $jobs->applications->where('status', 2)->first();
    //             // dd($checkStatus);
    //             $isWorked =  false;
    //             foreach ($jobs->applications as $application) {
    //                 if ($application->status == 3) {
    //                     $isWorked = true;
    //                 }
    //             }
    //             if ($checkStatus && !$isWorked) {
    //                 $data[$key]['job_id']           = $jobs->id;
    //                 $data[$key]['job_title']        = $jobs->job_title;
    //                 $data[$key]['job_location']     = $jobs->job_location;
    //                 $data[$key]['job_salary']       = $jobs->job_salary;
    //                 $data[$key]['job_start_date']   = $jobs->job_start_date;
    //                 $data[$key]['job_end_date']     = $jobs->job_end_date;
    //                 $data[$key]['Work_status']      = ApplicationStatusHelper::getTimesheetStatusByStatus(1);
    //             }
    //         }
    //         return response()->json([
    //             'message' => 'Applications for client',
    //             'status' => 'OK',
    //             'code' => 200,
    //             'data' => $data
    //         ], 200);
    //     }
    //     elseif ($status == 3) {
    //         foreach ($jobcheckApplication1 as $key => $jobs) {
    //             // dd($jobs);
    //             $checkStatus = $jobs->applications->where('status', 3)->first();
    //             // dd($checkStatus);
    //             if ($checkStatus) {
    //                 $data[$key]['job_id']           = $jobs->id;
    //                 $data[$key]['job_title']        = $jobs->job_title;
    //                 $data[$key]['job_location']     = $jobs->job_location;
    //                 $data[$key]['job_salary']       = $jobs->job_salary;
    //                 $data[$key]['job_start_date']   = $jobs->job_start_date;
    //                 $data[$key]['job_end_date']     = $jobs->job_end_date;
    //                 $data[$key]['Work_status']      = ApplicationStatusHelper::getTimesheetStatusByStatus(2);
    //             }
    //         }
    //         return response()->json([
    //             'message' => 'Check Timesheet for candidate',
    //             'status' => 'OK',
    //             'code' => 200,
    //             'data' => $data
    //         ], 200);
    //     }
    // }elseif($StatusType == 2){
    //     if($timesheetstatus == 2){
    //         // dd($jobcheckApplication1); 
    //         foreach ($jobcheckApplication1 as $key => $jobs) {
    //             dd($jobs->applications);
    //             foreach($jobs->applications as $application){
    //                 $timesheet_id = Application::where('timesheet_id',$application->timesheet_id )->get();
    //             }
    //             dd($timesheet_id);
    //             $checkStatus = $jobs->applications->where('timesheet_id',$timesheet_id->timesheet_id )->first();
    //             if($checkStatus){
    //                 $TimesheetStatusForClient = Timesheet::where('status',2)->get();
    //             }
    //             dd($TimesheetStatusForClient);
    //             if ($TimesheetStatusForClient) {
    //                 $data[$key]['job_id']           = $jobs->id;
    //                 $data[$key]['job_title']        = $jobs->job_title;
    //                 $data[$key]['job_location']     = $jobs->job_location;
    //                 $data[$key]['job_salary']       = $jobs->job_salary;
    //                 $data[$key]['job_start_date']   = $jobs->job_start_date;
    //                 $data[$key]['job_end_date']     = $jobs->job_end_date;
    //                 $data[$key]['Work_status']      = ApplicationStatusHelper::getTimesheetStatusByStatus(1);
    //             }
    //         }
    //         return response()->json([
    //             'message' => 'Timesheet Approve for candidate but payment is not done',
    //             'status' => 'OK',
    //             'code' => 200,
    //             'data' => $data
    //         ], 200);
    //     }
    //     return response()->json([
    //         'message' => 'No Timesheet for candidate',
    //         'status' => 'OK',
    //         'code' => 200,
    //         'data' => $data
    //     ], 200);
    // }


    /* /------------------- 1. Get all jobs for client------------------- /


        $job = Job::with('applications')->get();
        // dd($job);
        $candidate = Candidate::with('applications')->get();

        $status = $request->status;
        // dd($status);
        $data = [];
        if ($status == 1) {
            foreach ($job as $key => $jobs) {
                // dd('Hello');
                // $applications = Job::with('applications')->find($jobs->id);
                $applications = Job::with('applications')->where($jobs->application , $jobs->applications->status = 1)->get();
                dd($applications);
                if($applications){
                $data[$key]['job_id']           = $jobs->id;
                $data[$key]['job_title']        = $jobs->job_title;
                $data[$key]['job_location']     = $jobs->job_location;
                $data[$key]['job_salary']       = $jobs->job_salary;
                $data[$key]['job_start_date']   = $jobs->job_start_date;
                $data[$key]['job_end_date']     = $jobs->job_end_date;
                $data[$key]['total_applications']   = count($applications->applications);
                }
            }
            // dd($data);
            return response()->json([
                'message' => 'Applications for client',
                'status' => 'OK',
                'code' => 200,
                'data' => $data
            ], 200);
        } elseif ($status == 2) {

            foreach ($job->jobs as $key => $jobs) {
                $applications = Job::with('applications')->find($jobs->id);
                $applicationforstatus = Application::where('job_id', $jobs->id)->where('status', 2)->get();
                // dd($applicationforstatus);
                if ($applicationforstatus) {
                    $data[$key]['job_id']           = $jobs->id;
                    $data[$key]['job_title']        = $jobs->job_title;
                    $data[$key]['job_location']     = $jobs->job_location;
                    $data[$key]['job_salary']       = $jobs->job_salary;
                    $data[$key]['job_start_date']   = $jobs->job_start_date;
                    $data[$key]['job_end_date']     = $jobs->job_end_date;
                    $data[$key]['Work_status']      = ApplicationStatusHelper::getAfterStatusByStatus(1);
                    // dd($applicationforstatus);
                }
            }
            // dd($data);
            return response()->json([
                'message' => 'Booking for client',
                'status' => 'OK',
                'code' => 200,
                'data' => $data
            ], 200);
        } elseif ($status == 3) {
            foreach ($job->jobs as $key => $jobs) {
                $applications = Job::with('applications')->find($jobs->id);
                $applicationforstatus = Application::where(['job_id'=>$applications->job->id,'candidate_id'=>$jobs->applications,'status'=>3])->get();
                dd($applicationforstatus);
                if ($applicationforstatus) {
                    $data[$key]['job_id']           = $jobs->id;
                    $data[$key]['job_title']        = $jobs->job_title;
                    $data[$key]['job_location']     = $jobs->job_location;
                    $data[$key]['job_salary']       = $jobs->job_salary;
                    $data[$key]['job_start_date']   = $jobs->job_start_date;
                    $data[$key]['job_end_date']     = $jobs->job_end_date;
                    $data[$key]['Work_status']      = ApplicationStatusHelper::getAfterStatusByStatus(2);
                }
            }
            return response()->json([
                'message' => 'Timesheet for candidate',
                'status' => 'OK',
                'code' => 200,
                'data' => $data
            ], 200);
        }
        */

    public function approveApplication(ApproveRequest $request)
    {
        $applicationID = $request->application_id;

        $application = Application::with('job')->find($applicationID);
        // $applicationForClient = $application->job->client->working_status;
        // dd($applicationForClient);

        if (!$application) {
            return response()->json([
                'message' => 'Application not found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }

        $application->status = 2;
        $application->job->job_status = 2;
        $application->job->clientJobWorkingStatus = 2;

        $application->save();
        $application->job->save();

        return response()->json([
            'message' => 'Application successfully approved',
            'status' => 'OK',
            'code' => 200
        ], 200);
    }

    // public function BookingCandidate($id)
    // { 
    // }

    // $job = Job::with('applications')->with('timesheets')->find($id);
    //     dd($job);

    //     if (!$job) {
    //         return response()->json([
    //             'message' => 'Job not found',
    //             'status' => 'Bad Request',
    //             'code' => 400
    //         ], 400);
    //     }
    //     $data = [];
    //     $status = '';
    //     $applicationsS2 = Application::where(['job_id' => $job->id, 'status' => 2])->get();
    //     $applicationsS3 = Application::where(['job_id' => $job->id, 'status' => 3])->get();
    //     // dd(count($applications));
    //     if (count($applicationsS2) !== 0) {
    //         // dd('if');
    //         foreach ($applicationsS2 as $key => $application) {
    //             $candidate = Candidate::find($application->candidate_id);
    //             $data[$key]['candidate_id'] = $candidate->id;
    //             $data[$key]['candidate_name'] = $candidate->first_name . ' ' . $candidate->last_name;
    //             $data[$key]['job_id'] = $job->id;
    //             $data[$key]['job_title'] = $job->job_title;
    //             $data[$key]['job_location'] = $job->job_location;
    //             $data[$key]['job_salary'] = $job->job_salary;
    //             $data[$key]['job_start_date'] = $job->job_start_date;
    //             $data[$key]['job_end_date'] = $job->job_end_date;
    //             $data[$key]['Work_status'] = ApplicationStatusHelper::getTimesheetStatusByStatus(1);
    //         }
    //         return response()->json([
    //             'message' => 'Booked Candidate',
    //             'status' => 'OK',
    //             'code' => 200,
    //             'data' => $data
    //         ], 200);
    //     } elseif (count($applicationsS3) !== 0) {
    //         foreach ($applicationsS3 as $key => $application) {
    //             $candidate = Candidate::find($application->candidate_id);
    //             $data[$key]['candidate_id'] = $candidate->id;
    //             $data[$key]['candidate_name'] = $candidate->first_name . ' ' . $candidate->last_name;
    //             $data[$key]['job_id'] = $job->id;
    //             $data[$key]['job_title'] = $job->job_title;
    //             $data[$key]['job_location'] = $job->job_location;
    //             $data[$key]['job_salary'] = $job->job_salary;
    //             $data[$key]['job_start_date'] = $job->job_start_date;
    //             $data[$key]['job_end_date'] = $job->job_end_date;
    //             $data[$key]['Work_status'] = ApplicationStatusHelper::getTimesheetStatusByStatus(2);
    //             $data[$key]['timesheet'] = $job->timesheets;
    //         }
    //         return response()->json([
    //             'message' => 'Worked Done Candidate',
    //             'status' => 'OK',
    //             'code' => 200,
    //             'data' => $data
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'message' => 'No Candidate Booked',
    //             'status' => 'OK',
    //             'code' => 200,
    //             'data' => $data
    //         ], 200);
    //     }
    /********* Candidate Reject function *********/


    // public function afterApplicatonRejected(Request $request)
    // {
    //     $applicationID = $request->application_id;
    //     $rejectReason = $request->reason;

    //     $application = Application::find($applicationID);


    //     if (!$application) {
    //         return response()->json([
    //             'message' => 'Application not found',
    //             'status' => 'Bad Request',
    //             'code' => 400
    //         ], 400);
    //     }
    //     if (!$rejectReason) {
    //         return response()->json([
    //             'message' => 'Reason is required',
    //             'status' => 'Bad Request',
    //             'code' => 400
    //         ], 400);
    //     }
    //     $application->status = 2;
    //     $application->reject_reason = $rejectReason;

    //     $application->save();

    //     return response()->json([
    //         'message' => 'Application successfully rejected',
    //         'status' => 'OK',
    //         'code' => 200
    //     ], 200);
    // }

    public function approveTimesheet(Request $request)
    {
        $timesheetID = $request->timesheet_id;

        $timesheet = Timesheet::with('job', 'candidate')->find($timesheetID);
        // dd($timesheet);

        if (!$timesheet) {
            return response()->json([
                'message' => 'Timesheet not found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }

        $invoice = new Invoice;
        $invoice->job_id = $timesheet->job_id;
        $invoice->candidate_id = $timesheet->candidate_id;
        $invoice->client_id = $timesheet->job->client_id;
        $invoice->application_id = $timesheet->application_id;
        $invoice->timesheet_id = (int)$timesheetID;
        // dd($invoice->timesheet_id);

        $invoice->invoice_amount = $timesheet->job->job_salary;
        $invoice->invoice_status = 1;
        $invoice->invoice_date = date('Y-m-d');
        $invoice->save();

        $invoice->ref_no = 'INV-' . ($invoice->id + 1000);
        $invoice->save();

        $timesheet->status = 2;
        $timesheet->job->clientJobWorkingStatus = 3;
        $timesheet->candidate->working_status = 2;

        $timesheet->save();
        $timesheet->job->save();
        $timesheet->candidate->save();

        return response()->json([
            'message' => 'Timesheet successfully approved',
            'status' => 'OK',
            'code' => 200
        ], 200);
    }

    public function rejectTimesheet(Request $request)
    {
        $timesheetID = $request->timesheet_id;
        $rejectReason = $request->reason;

        $timesheet = Timesheet::find($timesheetID);

        if (!$timesheet) {
            return response()->json([
                'message' => 'Timesheet not found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }
        if (!$rejectReason) {
            return response()->json([
                'message' => 'Reason is required',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }

        $timesheet->status = 3;
        $timesheet->reject_reason = $rejectReason;
        // $timesheet->job->clientJobWorkingStatus = 1;
        $timesheet->candidate->working_status = 4;

        $timesheet->save();
        // $timesheet->job->save();
        $timesheet->candidate->save();

        return response()->json([
            'message' => 'Timesheet successfully rejected',
            'status' => 'OK',
            'code' => 200
        ], 200);
    }

    public function updateTimesheet(Request $request)
    {
        $id = $request->timesheet_id;
        $timesheet = Timesheet::with('job', 'application', 'candidate')->find($id);
        // dd($timesheet);

        $timesheet->start_time = $request->start_time;
        $timesheet->end_time = $request->end_time;
        $timesheet->break_time = $request->break_time;
        $timesheet->status = 1;

        $timesheet->save();

        $timesheet->candidate->working_status = 1;
        $timesheet->candidate->save();
        $timesheet->job->unit = $request->units;
        $timesheet->job->save();

        return response()->json([
            'message' => 'Timesheet Updated',
            'status' => 'OK',
            'code' => 200
        ], 200);
    }

    public function MarkAsPaid(Request $request)
    {
        $invoiceID = $request->invoice_id;

        $invoice = Invoice::with('candidate')->find($invoiceID);
        // dd($invoice);

        if (!$invoice) {
            return response()->json([
                'message' => 'Invoice not found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }

        $invoice->invoice_status = 2;
        $invoice->candidate->working_status = 3;

        $invoice->save();
        $invoice->candidate->save();

        return response()->json([
            'message' => 'Invoice successfully paid',
            'status' => 'OK',
            'code' => 200
        ], 200);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $job = Job::with('applications')->find($id);

        if (!$job) {
            return response()->json([
                'message' => 'Job not found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }

        $data = [];
        foreach ($job->applications as $key => $application) {
            $candidate = Candidate::find($application->candidate_id);
            $name = $candidate->first_name . ' ' . $candidate->last_name;
            $data[$key]['ref_no'] = $job->ref_no;
            $data[$key]['job_id'] = $job->id;
            $data[$key]['job_title'] = $job->job_title;
            $data[$key]['candidate_name'] = $name;
            $data[$key]['candidate_id'] = $candidate->id;
            $data[$key]['job_start_time'] = $job->job_start_time;
            $data[$key]['application_id'] = $application->id;
            $data[$key]['rate'] = $job->job_salary;
        }

        return response()->json([
            'message' => 'Applications for client',
            'status' => 'OK',
            'code' => 200,
            'data' => $data
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
