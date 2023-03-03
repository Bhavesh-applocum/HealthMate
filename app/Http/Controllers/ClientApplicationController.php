<?php

namespace App\Http\Controllers;

use App\Application;
use App\Candidate;
use App\Client;
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

    public function applicationforclient($id)
    {
        $job = Client::with('jobs')->find($id);
        // dd($job);
        $data = [];
        foreach ($job->jobs as $key => $jobs) {
            $applications = Job::with('applications')->find($jobs->id);
            $data[$key]['job_id']           = $jobs->id;
            $data[$key]['job_title']        = $jobs->job_title;
            $data[$key]['job_location']     = $jobs->job_location;
            $data[$key]['job_salary']       = $jobs->job_salary;
            $data[$key]['job_start_date']   = $jobs->job_start_date;
            $data[$key]['job_end_date']     = $jobs->job_end_date;
            $data[$key]['total_applications']   = count($applications->applications);
        }
        // dd($data);
        return response()->json([
            'message' => 'Applications for client',
            'status' => 'OK',
            'code' => 200,
            'data' => $data
        ], 200);
    }

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

        $application->status = 1;

        $application->save();

        return response()->json([
            'message' => 'Application successfully approved',
            'status' => 'OK',
            'code' => 200
        ], 200);
    }

    public function afterApplicatonRejected(Request $request)
    {
        $applicationID = $request->application_id;
        $rejectReason = $request->reason;

        $application = Application::find($applicationID);


        if (!$application) {
            return response()->json([
                'message' => 'Application not found',
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
        $application->status = 2;
        $application->reject_reason = $rejectReason;
        
        $application->save();

        return response()->json([
            'message' => 'Application successfully rejected',
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
