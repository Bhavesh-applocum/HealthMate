<?php

namespace App\Http\Controllers\Api;

use App\Address;
use App\Application;
use App\Candidate;
use App\Client;
use App\Helpers\ApplicationStatusHelper;
use App\Helpers\CandidateHelper;
use App\Helpers\CustomPaginationHelper;
use App\Helpers\JobHelper;
use App\Http\Requests\JobListRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\JobRequest;
use App\Http\Requests\JobUpdateRequest;
use App\Invoice;
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
        $jobs = Job::with('client', 'address');
        if (!$jobs) {
            return response()->json([
                'message' => 'No jobs found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }
        $paginatedData = CustomPaginationHelper::paginate_data($jobs, request()->query('page') ?? 1);
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
            $data[$key]['job_location'] = ApplicationStatusHelper::getOnlyArea($value->address_id);
            $data[$key]['job_salary'] = $value->job_salary;
            $data[$key]['job_category'] = ApplicationStatusHelper::getJobCategoryByName($value->job_category);
            $data[$key]['job_start_time'] = Carbon::createFromFormat('H:i:s', $value->job_start_time)->format('H:i A');
            $data[$key]['job_end_time'] = Carbon::createFromFormat('H:i:s', $value->job_end_time)->format('H:i A');
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
        $today = date('Y-m-d');
        // dd($jobs->get());
        $jobs = Job::with('client', 'applications')
            ->select('jobs.*')
            ->leftJoin('applications', 'applications.job_id', "jobs.id")
            ->where('job_category', $candidate->role)
            ->whereIn('jobs.job_status',[0, 1])
            ->whereNotIn('jobs.id', function ($query) use ($id) {
                $query->select('job_id')
                    ->from(with(new Application())->getTable())
                    ->where(function ($q1) use ($id) {
                        $q1->where("status", 1)
                            ->where("candidate_id", "=", $id);
                    });
            })
            ->where('job_date', '>=', $today)
            ->groupBy('jobs.id');
            // dd($jobs->count());
            // dd(count($jobs->get()));
        $paginatedData = CustomPaginationHelper::paginate_data($jobs, request()->query('page') ?? 1);
        if (count($paginatedData['data']) == 0) {
            return response()->json([
                'success' => true,
                'message' => 'No job found',
                'code'  => 400,
            ], 400);
        }
        $data = [];

        foreach ($paginatedData['data'] as $key => $job) {
            $data[$key]['id']           = $job->id;
            $data[$key]['job_title']        = $job->job_title;
            $data[$key]['job_location']     = ApplicationStatusHelper::getOnlyArea($job->address_id);
            $data[$key]['job_salary']       = $job->job_salary;
            $data[$key]['job_date']         = Carbon::createFromFormat('Y-m-d', $job->job_date)->format('d-m-Y');
            $data[$key]['job_start_time']   = Carbon::createFromFormat('H:i:s', $job->job_start_time)->format('H:i A');
            $data[$key]['job_end_time']     = Carbon::createFromFormat('H:i:s', $job->job_end_time)->format('H:i A');
            $data[$key]['job_category']     = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
        }
        // dd($paginatedData['last_page']);
        return response()->json([
            'success' => true,
            'code'  => 200,
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

        $today = date('Y-m-d');
        $jobs = Job::with('client', 'applications')
            ->select('jobs.*')
            ->leftJoin('applications', 'applications.job_id', "jobs.id")
            ->where('job_category', $candidate->role)
            ->whereIn('jobs.job_status', [0, 1])


            ->whereNotIn('jobs.id', function ($query) use ($id) {
                $query->select('job_id')
                    ->from(with(new Application())->getTable())
                    ->where(function ($q1) use ($id) {
                        $q1->where("status", 1)
                            ->where("candidate_id", "=", $id);
                    });
            })
            ->whereDate('jobs.job_date', '>=', $today)
            ->groupBy('jobs.id')
            ->whereNotIn('jobs.job_status', [2, 3]);
        $paginatedData = CustomPaginationHelper::mainPage_data($jobs, request()->query('page') ?? 1);
        $applicationWithJobs = Application::with('job')->where(['candidate_id'=>$candidate->id])->whereNotIn('status',[2,3])->get();
        $jobCount = 0;
            if($applicationWithJobs){
                foreach($applicationWithJobs as $aplcnWithJob){
                    if($aplcnWithJob->job->job_status == 1){
                        $jobCount++;
                    }
                }
            }
        $BookedCount = Application::where(['candidate_id'=>$candidate->id,'status'=>2])->count();
        $WorkedCount = Application::where(['candidate_id'=>$candidate->id,'status'=>3])->count();
        $totalPayment = Invoice::where(['candidate_id'=>$candidate->id,'invoice_status'=>2])->sum('invoice_amount');
        if (count($paginatedData['data']) == 0) {
            return response()->json([
                'success' => true,
                'code'   => 400,
                'message' => 'No job found',
                'AppliedCount' => $jobCount,
                'BookedCount' => $BookedCount,
                'WorkedCount' => $WorkedCount,
                'totalPayment' => $totalPayment,
            ], 400);
        }
        $data = [];

        foreach ($paginatedData['data'] as $key => $job) {
            $data[$key]['id']           = $job->id;
            $data[$key]['job_title']        = $job->job_title;
            $data[$key]['job_location']     = ApplicationStatusHelper::getOnlyArea($job->address_id);
            $data[$key]['job_salary']       = $job->job_salary;
            $data[$key]['job_date']         = Carbon::createFromFormat('Y-m-d', $job->job_date)->format('d-m-Y');
            $data[$key]['job_start_time']   = Carbon::createFromFormat('H:i:s', $job->job_start_time)->format('H:i A');
            $data[$key]['job_end_time']     = Carbon::createFromFormat('H:i:s', $job->job_end_time)->format('H:i A');
            $data[$key]['job_category']     = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
        }

        return response()->json([
            'success' => true,
            'code'    => 200,
            'message' => 'Jobs found',
            'data' => $data,
            'AppliedCount' => $jobCount,
            'BookedCount' => $BookedCount,
            'WorkedCount' => $WorkedCount,
            'totalPayment' => $totalPayment,
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
                'code'    => 400,
                'message' => 'No job found',
            ], 400);
        }
        $avatars = [];
        $data = [];
        if (!$client) {
            return response()->json([
                'success' => false,
                'code'    => 400,
                'message' => 'Client not found'
            ], 400);
        }
        $data1 = [];
        foreach ($paginatedData['data'] as $key => $job) {
            if ($job->job_status != 3) {

                $data['id']               = $job->id;
                $data['job_title']        = $job->job_title;
                $data['job_location']     = ApplicationStatusHelper::getOnlyArea($job->address_id);
                $data['job_salary']       = $job->job_salary;
                $data['job_date']         = Carbon::createFromFormat('Y-m-d', $job->job_date)->format('d-m-Y');
                $data['job_end_time']     = Carbon::createFromFormat('H:i:s', $job->job_end_time)->format('H:i A');
                $data['job_start_time']   = Carbon::createFromFormat('H:i:s', $job->job_start_time)->format('H:i A');
                $data['job_category']     = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
                $data1[] = $data;
            }
        }
        $payment = [];
        $jobCount = Job::where('client_id',$client->id)->whereNotIn('job_status',[3])->count();
        $TimesheetCount = Job::where(['client_id'=>$client->id,'clientJobWorkingStatus'=>2])->count();
        $InvoiceCount = Job::where(['client_id'=>$client->id,'job_status'=>1,'clientJobWorkingStatus'=>1])->count();
        $AllPayment  = Invoice::where(['client_id'=>$client->id,'invoice_status'=>2])->sum('invoice_amount');

        // dd($paginatedData['last_page']);//

        return response()->json([
            'message' => 'Jobs retrieved successfully',
            'success' => true,
            'code' => 200,
            'data' => $data1,
            'ContractCount' => $jobCount,
            'TimesheetCount' => $TimesheetCount,
            'InvoiceCount' => $InvoiceCount,
            'AllPayment' => $AllPayment,
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

        $client = Client::find($id);
        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'No client found'
            ], 400);
        }
        
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

        $job->job_title = $request->job_title;
        $job->job_description = $request->job_description;

        $job->job_salary = $request->job_salary;
        $job->job_date = $request->job_date;

        $job->job_category = $request->job_category;
        $job->job_start_time = $request->job_start_time;
        $job->job_end_time = $request->job_end_time;
        $job->break_time = $request->break_time;
        $job->address_id = $request->address_id;
        
        $job->client_id = $client->id;
        
        $job->parking = $request->parking;

        if ($request->unit != 0) {
            $job->unit = $request->unit;
        }

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
        $job = Job::where('id', $id)->with('client', 'timesheets','invoices')->first();
        
        if ($job) {
            $applicationsIDS = JobHelper::getApplicationIDsfromJob($job);
            
            
            $candidateObj = [];
            if ($job->job_status == 1) {
                $MultipleCandidatesForJob = JobHelper::getCandidateDataForJob($job);
                foreach ($MultipleCandidatesForJob as $key => $can) {
                    $canData = CandidateHelper::getCandidateField($can['id'], ['avatar', 'full_name', 'role']);
                    
                    $canData['candidate_id']   = $can['id'];
                    $canData['application_id'] = $can['application_id'];
                    array_push($candidateObj, $canData);
                }
                
            } else if ($job->job_status == 2) {
                
                $SingleCandidateForJob = JobHelper::getBookedCandidateToTheJob($job);
                $canData = CandidateHelper::getCandidateField($SingleCandidateForJob[0]['id'],['avatar','full_name','role']);

                $canData['candidate_id'] = $SingleCandidateForJob[0]['id'];
                $canData['application_id'] = $SingleCandidateForJob[0]['application_id'];
                
                array_push($candidateObj, $canData);
            } 
            else if ($job->job_status == 3){
                $SingleCandidateForJob = JobHelper::getWorkedCandidateToTheJob($job);
                $canData = CandidateHelper::getCandidateField($SingleCandidateForJob[0]['id'],['avatar','full_name','role']);

                $canData['candidate_id'] = $SingleCandidateForJob[0]['id'];
                
                $canData['application_id'] = $SingleCandidateForJob[0]['application_id'];
                
                array_push($candidateObj, $canData);
            }
            
            $data['job_title'] = $job->job_title;
            
            $data['candidates']          = isset($candidateObj) ? $candidateObj : [];
            $data['job_description']     = $job->job_description;
            $data['job_salary']          = $job->job_salary;
            $data['unit']                = $job->unit;
            $data['timesheet_id']        = isset($job->timesheets->id) ? $job->timesheets->id : 0;
            $data['timesheet_start_time']= isset($job->timesheets->start_time) ? Carbon::createFromFormat('H:i:s', $job->timesheets->start_time)->format('H:i') : '';
            $data['timesheet_end_time']  = isset($job->timesheets->end_time) ? Carbon::createFromFormat('H:i:s', $job->timesheets->end_time)->format('H:i') : '';
            $data['timesheet_break_time'] = isset($job->timesheets->break_time) ? Carbon::createFromFormat('H:i:s', $job->timesheets->break_time)->format('H:i') : '';
            if($job->job_status == 2){
            $data['cordinates']          = ApplicationStatusHelper::getLatitudeAndLongtitude();
            }
            $data['job_location']        = ApplicationStatusHelper::getFullClientAddress($job->address_id);
            $data['job_date']            = Carbon::createFromFormat('Y-m-d', $job->job_date)->format('d-m-Y');
            
            $data['job_start_time']      = date("H:i", strtotime($job->job_start_time));
            $data['job_end_time']        = date("H:i", strtotime($job->job_end_time));
            $data['timesheet_status']    = (isset($job->timesheets) && isset($job->timesheets->status)) ? ApplicationStatusHelper::getTimesheetStatusByStatus($job->timesheets->status) : '';
            if($job->job_status == 3){
                $data['candidate_working_status'] = ApplicationStatusHelper::getAfterWorkingStatusForCandidateByName($job->candidate_working_status);
            }
            $data['job_category']        = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
            $data['parking']             = ApplicationStatusHelper::getParkingByName($job->parking);
            $data['break_time']          = Carbon::createFromFormat('H:i:s', $job->break_time)->format('H:i');
            $data['reject_reason']       = isset($job->timesheets->reject_reason) ? $job->timesheets->reject_reason : '';
            

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
     *p
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
            if ($application->status == 1 || $application->status == 2 || $application->status == 3) {
                return response()->json([
                    'message'   => 'Job Has Been '. ApplicationStatusHelper::getApplicationStatusByName($application->status) .' So,Cannot be Deleted',
                    'status'    => 'Not Found',
                    'code'      => 404,
                ], 404);
            }
        }
        $job->delete();

        return response()->json([
            'message'   => 'Job Deleted Successfully',
            'status'    => 'OK',
            'code'      => 200,
            'data'      => ['job' => $job,]
        ], 200);
    }
}
