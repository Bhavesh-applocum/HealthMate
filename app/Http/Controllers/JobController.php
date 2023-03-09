<?php

namespace App\Http\Controllers;

use App\Application;
use App\Candidate;
use App\Client;
use App\Helpers\ApplicationStatusHelper;
use App\Http\Requests\JobRequest;
use App\Http\Requests\JobUpdateRequest;
use App\Job;
use Carbon\Carbon;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $job = Job::all();

        if(!$job) {
            return response()->json([
                'message' => 'No jobs found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }

        return response()->json([
            'message' => 'Jobs found',
            'status' => 'OK',
            'code' => 200,
            'data' => $job
        ], 200);
    }

    public function specificJob($id)
    {
        $candidate = Candidate::find($id);
        
        if(!$candidate) {
            return response()->json([
                'message' => 'Candidate not found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }

        $jobs = Job::with('client')->with('applications')->get();
        // dd($jobs);

        $data = [];

        foreach ($jobs as $key => $job) {
            
            $isBooked = false;
            foreach($job->applications as $application) {
                if($application->status == 2) {
                    $isBooked = true;
                   
                }
            }
            if ($candidate->role == $job->job_category && !$isBooked && $job->job_start_date > Carbon::now()) {
                $data[$key]['client_id']        = $job->client->practice_name; 
                $data[$key]['job_id']           = $job->id; 
                $data[$key]['job_title']        = $job->job_title;
                $data[$key]['job_description']  = $job->job_description;
                $data[$key]['job_location']     = $job->job_location;
                $data[$key]['job_salary']       = $job->job_salary;
                $data[$key]['job_start_date']   = $job->job_start_date;
                $data[$key]['job_end_date']     = $job->job_end_date;
                $data[$key]['job_start_time']   = $job->job_start_time;
                $data[$key]['job_end_time']     = $job->job_end_time;
                $data[$key]['break_time']       = $job->break_time;
                $data[$key]['job_status']       = ApplicationStatusHelper::getJobStatusByName($job->job_status);
                $data[$key]['job_type']         = ApplicationStatusHelper::getJobTypeByName($job->job_type);
                $data[$key]['job_category']     = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
            }
        }
        // for particular candidate 
        return response()->json([
            'success' => true,
            'data' => $data
        ], 200);

    }
    
    public function clientJobs($id)
    {

        $client = Client::find($id);
        // dd($client);
        $jobs = Job::where('client_id', $id)->get();
        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found'
            ], 400);
        }

        if (!$jobs || sizeof($jobs) == 0) {
            return response()->json([
                'success' => false,
                'message' => 'No job found'
            ], 400);
        }
        $data = [];
        // echo "<pre>";
        foreach ($jobs as $key => $job) {
            if($job->job_end_date > Carbon::now()){
                $job->job_status = ApplicationStatusHelper::getJobStatusByName($job->job_status);
                $job->job_type = ApplicationStatusHelper::getJobTypeByName($job->job_type);
                $job->job_category = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
                $data[$key]        = $job;
            }
        }

        return response()->json([
            'success' => true,
            'data' => $data,
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
    public function store(JobRequest $request)
    {
        $id = $request->id;
        // dd('done');

        $client = Client::find($id);
        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'No client found'
            ], 400);
        }
        $allJobtypes = config('constant.job_type');
        $allJobCategory = config('constant.job_Category');
        $allJobStatus = config('constant.job_status');

        $job = new Job;
        $jobStatus = '';
        for ($i = 1; $i <= count($allJobStatus); $i++) {
            if ($request->job_status == $i) {
                $jobStatus = $allJobStatus[$i];
            }
        };

        $jobCategory = '';
        for ($i = 1; $i <= count($allJobCategory); $i++) {
            if ($request->job_category == $i) {
                $jobCategory = $allJobCategory[$i];
            }
        };

        $jobType = '';
        for ($i = 1; $i <= count($allJobtypes); $i++) {
            if ($request->job_type == $i) {
                $jobType = $allJobtypes[$i];
            }
        };

        $job->job_title = $request->job_title;
        $job->job_description = $request->job_description;
        $job->job_location = $request->job_location;
        $job->job_type = $request->job_type;
        
        $job->job_salary = $request->job_salary;
        $job->job_start_date = $request->job_start_date;
        $job->job_end_date = $request->job_end_date;
        $job->job_status = $request->job_status;

        $job->job_category = $request->job_category;
        $job->job_start_time = $request->job_start_time;
        $job->job_end_time = $request->job_end_time;
        $job->break_time = $request->break_time;

        $job->admin_time = $request->admin_time;
        $job->client_id = $client->id;
        $job->created_at =  now();
        $job->updated_at = now();

        $job->save();

        $job->ref_no = 'CON-' . ($job->id + 10000);
        $job->save();

        return response()->json([
            'message' => 'Job Created Successfully',
            'status' => 'OK',
            'code' => 200,
            'data' => [
                'job' => $job,
            ]
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $job = Job::find($id);

        if ($job) {
            $job->job_status = ApplicationStatusHelper::getJobStatusByName($job->job_status);
            $job->job_category = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
            $job->job_type = ApplicationStatusHelper::getJobTypeByName($job->job_type);

            return response()->json([
                'message'   => 'Job Details',
                'status'    => 'OK',
                'code'      => 200,
                'data'      => ['job' => $job]
            ], 200);
        } else {
            return response()->json([
                'message'   => 'Job Not Found',
                'status'    => 'Not Found',
                'code'      => 404,
                'data'      => ['job' => $job,]
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $job = Job::find($id);

        if (!$job) {
            return response()->json([
                'message'   => 'Job Not Found',
                'status'    => 'Not Found',
                'code'      => 404,
                'data'      => ['job' => $job,]
            ], 404);
        }
        $job->job_status = ApplicationStatusHelper::getJobStatusByName($job->job_status);
        $job->job_category = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
        $job->job_type = ApplicationStatusHelper::getJobTypeByName($job->job_type);

        return response()->json([
            'message'   => 'Job Details',
            'status'    => 'OK',
            'code'      => 200,
            'data'      => ['job' => $job]
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function update($id, JobUpdateRequest $request, Job $job)
    {
        //
    }

    public function jobupdate($id, JobUpdateRequest $request, Job $job)
    {
        $job = Job::find($id);
        if (!$job) {
            return response()->json([
                'message'   => 'Job Not Found',
                'status'    => 'Not Found',
                'code'      => 404,
                'data'      => ['job' => $job,]
            ], 404);
        }

        // $client_id = $job->client_id;
        // $client = Client::find($client_id);

        // if(!$client){
        //     return response()->json([
        //         'message'   => 'Client Not Found',
        //         'status'    => 'Not Found',
        //         'code'      => 404,
        //         'data'      => ['client' => $client,]
        //     ], 404);
        // }

        // dd('job and client found');
        $allJobtypes = config('constant.job_type');
        $allJobCategory = config('constant.job_Category');
        $allJobStatus = config('constant.job_status');

        $jobStatus = '';
        for ($i = 1; $i <= count($allJobStatus); $i++) {
            if ($request->job_status == $i) {
                $jobStatus = $allJobStatus[$i];
            }
        };

        $jobCategory = '';
        for ($i = 1; $i <= count($allJobCategory); $i++) {
            if ($request->job_category == $i) {
                $jobCategory = $allJobCategory[$i];
            }
        };

        $jobType = '';
        for ($i = 1; $i <= count($allJobtypes); $i++) {
            if ($request->job_type == $i) {
                $jobType = $allJobtypes[$i];
            }
        };

        $job->job_title = $request->job_title;
        $job->job_description = $request->job_description;
        $job->job_location = $request->job_location;
        $job->job_type = $request->job_type;

        $job->job_salary = $request->job_salary;
        $job->job_start_date = $request->job_start_date;
        $job->job_end_date = $request->job_end_date;
        $job->job_status = $request->job_status;

        $job->job_category = $request->job_category;
        $job->job_start_time = $request->job_start_time;
        $job->job_end_time = $request->job_end_time;
        $job->break_time = $request->break_time;
        $job->admin_time = $request->admin_time;

        $job->updated_at = now();

        // dd($job);
        $job->save();

        return response()->json([
            'message'   => 'Job Updated Successfully',
            'status'    => 'OK',
            'code'      => 200,
            'data'      => ['job' => $job,]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Job  $job
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $job = Job::find($id);
        if (!$job) {
            return response()->json([
                'message'   => 'Job Not Found',
                'status'    => 'Not Found',
                'code'      => 404,
                'data'      => ['job' => $job,]
            ], 404);
        }

        $applications = Job::with('applications')->where('id', $id)->first();
        foreach($applications->applications as $application){
            if ($application->status == 1) {
                return response()->json([
                    'message'   => 'Job Has Been Applied So,Cannot be Deleted',
                    'status'    => 'Not Found',
                    'code'      => 404,
                ], 404);
            }
        }


        // if ($job->job_status == ) {
        //     // dd()
        //     dd($job->job_status);
        //     return response()->json([
        //         'message'   => 'Job Has Been Applied So,Cannot be Deleted',
        //         'status'    => 'Not Found',
        //         'code'      => 404,
        //     ], 404);
        // }
        $job->delete();

        return response()->json([
            'message'   => 'Job Deleted Successfully',
            'status'    => 'OK',
            'code'      => 200,
            'data'      => ['job' => $job,]
        ], 200);
    }
}
