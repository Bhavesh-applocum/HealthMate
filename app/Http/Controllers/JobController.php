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
use Illuminate\Support\Facades\DB;

class JobController extends Controller
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

    public function findJobs(){
        $job = DB::table("jobs")->paginate(10);
        // dd($job);
        if(!$job){
            return response()->json([
                'message' => 'No jobs found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }
        $data = [];

        $totalPages = $job->lastPage();
        $isFirstPage = ($job->currentPage() == 1);
        $nextPageNumber = $job->nextPageUrl() ? $job->currentPage() + 1 : 0;
        $previousPageNumber = $job->previousPageUrl() ? $job->currentPage() - 1 : 0;

        $pagination = [
            'total' => $job->total(),
            'total_pages' => $totalPages,
            'first_page' => $isFirstPage,
            'last_page' => $job->hasMorePages(),
            'previous_page' => $previousPageNumber,
            'next_page' => $nextPageNumber,
            // 'out_of_bounds' => $job->hasPages() && ! ($job->currentPage()),
            // 'offset' => ($job->currentPage() - 1) * $job->perPage(),
        ];

        foreach ($job as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['ref_no'] = $value->ref_no;
            $data[$key]['job_title'] = $value->job_title;
            $data[$key]['job_location'] = $value->job_location;
            $data[$key]['job_salary'] = $value->job_salary;
            $data[$key]['job_category'] = ApplicationStatusHelper::getJobCategoryByName($value->job_category);
            $data[$key]['job_start_time'] = $value->job_start_time;
            $data[$key]['job_end_time'] = $value->job_end_time;
        }
            if($job){
                return response()->json([
                    'message' => 'Jobs found',
                    'status' => 'OK',
                    'code' => 200,
                    'data' => $data
                ], 200)->header('X-pagination', json_encode($pagination));
            }
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
            // dd($job);
            $isBooked = false;
            foreach($job->applications as $application) {
                if($application->status == 2) {
                    $isBooked = true;
                   
                }
            }

            $isWorked = false;
            foreach($job->applications as $application) {
                if($application->status == 3) {
                    $isWorked = true;
                }
            }
            // dd($candidate->role == $job->job_category);
            // dd($job->job_end_time > Carbon::now()->toDateTimeString());
            if ($candidate->role == $job->job_category && !$isBooked && !$isWorked && $job->job_date > Carbon::now()->toDateTimeString()) {
                // print_r($job->id);
                // dd($candidate->role);
                // dd($job->job_category);
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
                $data[$key]['job_category']     = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
            }
        }
        // dd($data);
        // for particular candidate 
        return response()->json([
            'success' => true,
            'data' => [$data]
        ], 200);

    }
    
    public function clientJobs($id)
    {

        $client = Client::find($id);
        // dd($client);
        $jobs = Job::where('client_id', $id)->paginate(5);
        // dd($jobs);
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

        $totalPages = $jobs->lastPage();
        $isFirstPage = ($jobs->currentPage() == 1);
        $nextPageNumber = $jobs->nextPageUrl() ? $jobs->currentPage() + 1 : 0;
        $previousPageNumber = $jobs->previousPageUrl() ? $jobs->currentPage() - 1 : 0;


        $pagination = [
            'total' => $jobs->total(),
            'total_pages' => $totalPages,
            'first_page' => $isFirstPage,
            'last_page' => $jobs->hasMorePages(),
            'previous_page' => $previousPageNumber,
            'next_page' => $nextPageNumber,
            // 'out_of_bounds' => $job->hasPages() && ! ($job->currentPage()),
            // 'offset' => ($job->currentPage() - 1) * $job->perPage(),
        ];
        $data = [];
        // echo "<pre>";
        foreach ($jobs as $key => $job) {
            if($job->job_date > Carbon::now() || $job->job_end_time > Carbon::now()){
                $job->job_status = ApplicationStatusHelper::getJobStatusByName($job->job_status);
                $job->job_type = ApplicationStatusHelper::getJobTypeByName($job->job_type);
                $job->job_category = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
                $data[$key]        = $job;
            }
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ], 200)->header('X-pagination', json_encode($pagination));
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
        $id = $request->client_id;
        // dd('done');

        $client = Client::find($id);
        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'No client found'
            ], 400);
        }
        // $allJobtypes = config('constant.job_type');
        $allJobCategory = config('constant.job_Category');
        $allJobStatus = config('constant.job_status');
        $parking = config('constant.parking');

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

        $parkingStatus = '';
        for ($i = 1; $i <= count($parking); $i++) {
            if ($request->parking == $i) {
                $parkingStatus = $parking[$i];
            }
        };

        // $jobType = '';
        // for ($i = 1; $i <= count($allJobtypes); $i++) {
        //     if ($request->job_type == $i) {
        //         $jobType = $allJobtypes[$i];
        //     }
        // };

        $job->job_title = $request->job_title;
        $job->job_description = $request->job_description;
        // $job->job_location = $request->job_location;
        // $job->job_type = $request->job_type;
        
        $job->job_salary = $request->job_salary;
        $job->job_date = $request->job_date;
        // $job->job_end_date = $request->job_end_date;
        // $job->job_status = $request->job_status;

        $job->job_category = $request->job_category;
        $job->job_start_time = $request->job_start_time;
        $job->job_end_time = $request->job_end_time;
        $job->break_time = $request->break_time;
        
        // $job->admin_time = $request->admin_time;
        $job->client_id = $client->id;
        $job->visits = $request->visits;
        $job->parking = $request->parking;
        $job->unit = $request->unit;
        // $job->meals = $request->meals;

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
            // $job->job_type = ApplicationStatusHelper::getJobTypeByName($job->job_type);
            $job->parking = ApplicationStatusHelper::getParkingByName($job->parking);

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
