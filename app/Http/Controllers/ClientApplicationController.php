<?php

namespace App\Http\Controllers;

use App\Application;
use App\Candidate;
use App\Client;
use App\Helpers\ApplicationStatusHelper;
use App\Job;
use Illuminate\Http\Request;

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

    public function statusforclient($id, Request $request)
    {
        $job = Job::with('applications')->with('client')->get();
        $jobcheckApplication = Client::with('jobs')->find($id);
        $jobcheckApplication1 = $jobcheckApplication->jobs;
        // dd($jobcheckApplication1);
        // dd($job);
        $status = $request->status;
        // dd($status);

        $data = [];

        if ($status == 1) {
            foreach ($jobcheckApplication1 as $key => $jobs) {
                // dd($jobs);
                $checkStatus = $jobs->applications->where('status', 1)->first();
                $isBooked =  false;
                foreach ($jobs->applications as $application) {
                    if ($application->status == 2) {
                        $isBooked = true;
                    }
                }
                $isWorked =  false;
                foreach ($jobs->applications as $application) {
                    if ($application->status == 3) {
                        $isWorked = true;
                    }
                }
                // dd($checkStatus);
                if ($checkStatus && !$isBooked && !$isWorked) {
                    $data[$key]['job_id']           = $jobs->id;
                    $data[$key]['job_title']        = $jobs->job_title;
                    $data[$key]['job_location']     = $jobs->job_location;
                    $data[$key]['job_salary']       = $jobs->job_salary;
                    $data[$key]['job_start_date']   = $jobs->job_start_date;
                    $data[$key]['job_end_date']     = $jobs->job_end_date;
                    $data[$key]['total_applications']   = count($jobs->applications);
                }
            }
            return response()->json([
                'message' => 'Applications for client',
                'status' => 'OK',
                'code' => 200,
                'data' => $data
            ], 200);
        } elseif ($status == 2) {
            foreach ($jobcheckApplication1 as $key => $jobs) {
                // dd($jobs);
                $checkStatus = $jobs->applications->where('status', 2)->first();
                // dd($checkStatus);
                $isWorked =  false;
                foreach ($jobs->applications as $application) {
                    if ($application->status == 3) {
                        $isWorked = true;
                    }
                }
                if ($checkStatus && !$isWorked) {
                    $data[$key]['job_id']           = $jobs->id;
                    $data[$key]['job_title']        = $jobs->job_title;
                    $data[$key]['job_location']     = $jobs->job_location;
                    $data[$key]['job_salary']       = $jobs->job_salary;
                    $data[$key]['job_start_date']   = $jobs->job_start_date;
                    $data[$key]['job_end_date']     = $jobs->job_end_date;
                    $data[$key]['Work_status']      = ApplicationStatusHelper::getAfterStatusByStatus(1);
                }
            }
            return response()->json([
                'message' => 'Applications for client',
                'status' => 'OK',
                'code' => 200,
                'data' => $data
            ], 200);
        }
        elseif ($status == 3) {
            foreach ($jobcheckApplication1 as $key => $jobs) {
                // dd($jobs);
                $checkStatus = $jobs->applications->where('status', 3)->first();
                // dd($checkStatus);
                if ($checkStatus) {
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
                'message' => 'Check Timesheet for candidate',
                'status' => 'OK',
                'code' => 200,
                'data' => $data
            ], 200);
        }
    }


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

    public function approveApplication(Request $request)
    {

        $applicationID = $request->application_id;

        $application = Application::find($applicationID);

        if (!$application) {
            return response()->json([
                'message' => 'Application not found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }

        $application->status = 2;

        $application->save();

        return response()->json([
            'message' => 'Application successfully approved',
            'status' => 'OK',
            'code' => 200
        ], 200);
    }

    public function BookingCandidate($id)
    {
        $job = Job::with('applications')->with('timesheets')->find($id);
        // dd($job);

        if (!$job) {
            return response()->json([
                'message' => 'Job not found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }
        $data = [];
        $status = '';
        $applicationsS2 = Application::where(['job_id' => $job->id, 'status' => 2])->get();
        $applicationsS3 = Application::where(['job_id' => $job->id, 'status' => 3])->get();
        // dd(count($applications));
        if (count($applicationsS2) !== 0) {
            // dd('if');
            foreach ($applicationsS2 as $key => $application) {
                $candidate = Candidate::find($application->candidate_id);
                $data[$key]['candidate_id'] = $candidate->id;
                $data[$key]['candidate_name'] = $candidate->first_name . ' ' . $candidate->last_name;
                $data[$key]['job_id'] = $job->id;
                $data[$key]['job_title'] = $job->job_title;
                $data[$key]['job_location'] = $job->job_location;
                $data[$key]['job_salary'] = $job->job_salary;
                $data[$key]['job_start_date'] = $job->job_start_date;
                $data[$key]['job_end_date'] = $job->job_end_date;
                $data[$key]['Work_status'] = ApplicationStatusHelper::getAfterStatusByStatus(1);
            }
            return response()->json([
                'message' => 'Booked Candidate',
                'status' => 'OK',
                'code' => 200,
                'data' => $data
            ], 200);
        } elseif (count($applicationsS3) !== 0) {
            foreach ($applicationsS3 as $key => $application) {
                $candidate = Candidate::find($application->candidate_id);
                $data[$key]['candidate_id'] = $candidate->id;
                $data[$key]['candidate_name'] = $candidate->first_name . ' ' . $candidate->last_name;
                $data[$key]['job_id'] = $job->id;
                $data[$key]['job_title'] = $job->job_title;
                $data[$key]['job_location'] = $job->job_location;
                $data[$key]['job_salary'] = $job->job_salary;
                $data[$key]['job_start_date'] = $job->job_start_date;
                $data[$key]['job_end_date'] = $job->job_end_date;
                $data[$key]['Work_status'] = ApplicationStatusHelper::getAfterStatusByStatus(2);
                $data[$key]['timesheet'] = $job->timesheets;
            }
            return response()->json([
                'message' => 'Worked Done Candidate',
                'status' => 'OK',
                'code' => 200,
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'message' => 'No Candidate Booked',
                'status' => 'OK',
                'code' => 200,
                'data' => $data
            ], 200);
        }
    }

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
