<?php

namespace App\Http\Controllers;

use App\Application;
use App\Candidate;
use App\Client;
use App\Helpers\ApplicationStatusHelper;
use App\Helpers\CustomPaginationHelper;
use App\Http\Requests\JobListRequest;
use App\Http\Requests\JobRequest;
use App\Http\Requests\JobUpdateRequest;
use App\Job;
use Carbon\Carbon;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\Request as HttpRequest;
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

    public function findJobs()
    {
        $jobs = Job::with('client');
        // $job1 = Job::with('client')->get();
        // dd($job1);
        if (!$jobs) {
            return response()->json([
                'message' => 'No jobs found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }
        $paginatedData = CustomPaginationHelper::paginate_data($jobs, request()->query('page') ?? 1);
        // dd($paginatedData);
        if (count($paginatedData['data']) == 0) {
            return response()->json([
                'success' => true,
                'message' => 'No job found',
            ], 400);
        }
        $data = [];

        foreach ($paginatedData['data'] as $key => $value) {
            $data[$key]['id'] = $value->id;
            $data[$key]['ref_no'] = $value->ref_no;
            $data[$key]['job_title'] = $value->job_title;
            $data[$key]['job_location'] = $value->client->address;
            $data[$key]['job_salary'] = $value->job_salary;
            $data[$key]['job_category'] = ApplicationStatusHelper::getJobCategoryByName($value->job_category);
            $data[$key]['job_start_time'] = $value->job_start_time;
            $data[$key]['job_end_time'] = $value->job_end_time;
        }
        if ($jobs) {
            return response()->json([
                'message' => 'Jobs found',
                'status' => 'OK',
                'code' => 200,
                'data' => $data,
                'curent_page' => $paginatedData['current_page'],
                'last_page' => $paginatedData['last_page'],
                'is_last_page' => $paginatedData['is_last_page'],
            ], 200);
        }
    }

    public function specificJob($id)
    {
        $candidate = Candidate::find($id);

        if (!$candidate) {
            return response()->json([
                'message' => 'Candidate not found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }
        $jobs = Job::where('job_category', $candidate->role)->with('client')->with('applications');
        $paginatedData = CustomPaginationHelper::paginate_data($jobs, request()->query('page') ?? 1);
        if (count($paginatedData['data']) == 0) {
            return response()->json([
                'success' => true,
                'message' => 'No job found',
            ], 400);
        }
        $data = [];

        
        foreach ($paginatedData['data'] as $key => $job) {

            $isCandidate = false;
            foreach ($job->applications as $application) {
                if ($application->candidate_id == $candidate->id && $application->job_id == $job->id) {
                    $isCandidate = true;
                }
            }

            $isBooked = false;
            foreach ($job->applications as $application) {
                if ($application->status == 2) {
                    $isBooked = true;
                }
            }

            $isWorked = false;
            foreach ($job->applications as $application) {
                if ($application->status == 3) {
                    $isWorked = true;
                }
            }

            if ($candidate->role == $job->job_category &&!$isCandidate && !$isBooked && !$isWorked && $job->job_date > Carbon::now()->toDateTimeString()) {
                $data[$key]['id']           = $job->id;
                $data[$key]['job_title']        = $job->job_title;
                $data[$key]['job_location']     = $job->client->address;
                $data[$key]['job_salary']       = $job->job_salary;
                $data[$key]['job_date']         = $job->job_date;
                $data[$key]['job_start_time']   = $job->job_start_time;
                $data[$key]['job_end_time']     = $job->job_end_time;
                $data[$key]['job_category']     = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
            }
        }
        // for particular candidate 
        return response()->json([
            'success' => true,
            'message' => 'Jobs found',
            'data' => $data,
            'curent_page' => $paginatedData['current_page'],
            'last_page' => $paginatedData['last_page'],
            'is_last_page' => $paginatedData['is_last_page'],
        ], 200);
    }

    public function candidateDashboard($id)
    {
        $candidate = Candidate::find($id);

        if (!$candidate) {
            return response()->json([
                'message' => 'Candidate not found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }
        $jobs = Job::where('job_category', $candidate->role)->with('client')->with('applications');
        $paginatedData = CustomPaginationHelper::mainPage_data($jobs, request()->query('page') ?? 1);
        if (count($paginatedData['data']) == 0) {
            return response()->json([
                'success' => true,
                'message' => 'No job found',
            ], 400);
        }
        $data = [];

        
        foreach ($paginatedData['data'] as $key => $job) {

            $isCandidate = false;
            foreach ($job->applications as $application) {
                if ($application->candidate_id == $candidate->id && $application->job_id == $job->id ) {
                    $isCandidate = true;
                }
            }

            $isBooked = false;
            foreach ($job->applications as $application) {
                if ($application->status == 2) {
                    $isBooked = true;
                }
            }

            $isWorked = false;
            foreach ($job->applications as $application) {
                if ($application->status == 3) {
                    $isWorked = true;
                }
            }

            if ($candidate->role == $job->job_category &&!$isCandidate && !$isBooked && !$isWorked && $job->job_date > Carbon::now()->toDateTimeString()) {
                $data[$key]['id']           = $job->id;
                $data[$key]['job_title']        = $job->job_title;
                $data[$key]['job_location']     = $job->client->address;
                $data[$key]['job_salary']       = $job->job_salary;
                $data[$key]['job_date']         = $job->job_date;
                $data[$key]['job_start_time']   = $job->job_start_time;
                $data[$key]['job_end_time']     = $job->job_end_time;
                $data[$key]['job_category']     = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
            }
        }
        // for particular candidate 
        return response()->json([
            'success' => true,
            'message' => 'Jobs found',
            'data' => $data,
            'curent_page' => $paginatedData['current_page'],
            'last_page' => $paginatedData['last_page'],
            'is_last_page' => $paginatedData['is_last_page'],
        ], 200);
    }

    public function clientJobs($id)
    {
        $client = Client::find($id);
        $jobs = Job::where('client_id', $id);
        $paginatedData = CustomPaginationHelper::paginate_data($jobs, request()->query('page') ?? 1);

        if (count($paginatedData['data']) == 0) {
            return response()->json([
                'success' => true,
                'message' => 'No job found',
            ], 400);
        }
        $avatars=[];
        // dd($paginatedData);
        $data = [];
        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'Client not found'
            ], 400);
        }

        

        // echo "<pre>";
        $data1 = [];
        foreach ($paginatedData['data'] as $key => $job) {
            if ($job->job_date >= Carbon::now() || $job->job_end_time >= Carbon::now()) {
                
                $data['id']           = $job->id;
                $data['job_title']        = $job->job_title;
                $data['job_location']     = $job->client->address;
                $data['job_salary']       = $job->job_salary;
                $data['job_start_time']   = $job->job_start_time;
                $data['job_end_time']     = $job->job_end_time;
                $data['job_category']     = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
                $data1[] = $data;
            }
            // $data1 = [$data];
        }
        // dd($data);
        return response()->json([
            'message' => 'Jobs retrieved successfully',
            'success' => true,
            'code' => 200,
            'data' => $data1,
            'curent_page' => $paginatedData['current_page'],
            'last_page' => $paginatedData['last_page'],
            'is_last_page' => $paginatedData['is_last_page'],
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
        $job = Job::where('id', $id)->with('client')->first();
        if ($job) {
            $data['job_title'] = $job->job_title;
            $data['job_description'] = $job->job_description;
            $data['job_location'] = $job->client->address;
            $data['job_salary'] = $job->job_salary;
            $data['job_start_time'] = $job->job_start_time;
            $data['job_end_time'] = $job->job_end_time;
            $data['job_category'] = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
            $data['parking'] = ApplicationStatusHelper::getParkingByName($job->parking);
            $data['unit'] = $job->unit;
            $data['visits'] = $job->visits;

            return response()->json([
                'message'   => 'Job Details',
                'status'    => 'OK',
                'code'      => 200,
                'data'      => $data
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
        foreach ($applications->applications as $application) {
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
