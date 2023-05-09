<?php

namespace App\Http\Controllers\Api;

use App\Application;
use App\Candidate;
use App\Helpers\ApplicationStatusHelper;
use App\Helpers\CustomPaginationHelper;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\Job;
use App\Timesheet;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CandidateApplicationController extends Controller
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
        $candidate = Candidate::find($request->candidate_id);

        if (!$candidate) {
            return response()->json([
                'message' => 'Candidate not found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }

        $job = Job::with('client')->find($request->job_id);

        if (!$job) {
            return response()->json([
                'message' => 'Job not found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }

        if ($job->job_date < now() && $job->job_start_time <= now()) {
            return response()->json([
                'message' => 'Job already started',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }

        $application = new Application;

        $application->candidate_id = $candidate->id;
        $application->job_id = $job->id;
        $application->status = 1;
        $job->job_status = 1;
        $job->clientJobWorkingStatus = 1;

        $application->save();
        $job->save();

        return response()->json([
            'message' => 'Application successfully created',
            'status' => 'OK',
            'code' => 200
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function show($candidateID)
    {
        // 
    }

    public function showstautsjob(Request $request)
    {
        $candidateId = $request->candidate_id;
        $status = $request->status;

        $candidateApplication = Application::where(['candidate_id' => $candidateId, 'status' => $status])->with('candidate');
        $paginateData = CustomPaginationHelper::paginate_data($candidateApplication, request()->query('page') ?? 1);
        if (count($paginateData['data']) == 0) {
            return response()->json([
                'success' => true,
                'message' => 'No job found',
            ], 400);
        }

        if (!$candidateApplication) {
            return response()->json([
                'message' => 'Candidate Not Found',
                'status' => 'Bad Request',
                'code' => 400
            ]);
        }
        $data = [];
        if ($status == 1) {
            foreach ($paginateData['data'] as $key => $application) {
                $job = Job::with('applications')->whereNotIn('job_status', [2, 3])->find($application->job_id);
                if ($job) {
                    $dataObj['application_id']   = $application->id;
                    $dataObj['id']               = $job->id;
                    $dataObj['job_title']        = $job->job_title;
                    $dataObj['job_category']     = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
                    $dataObj['job_date']         = Carbon::createFromFormat('Y-m-d', $job->job_date)->format('d-m-Y');
                    $dataObj['job_start_time']   = Carbon::createFromFormat('H:i:s', $job->job_start_time)->format('H:i A');
                    $dataObj['job_end_time']     = Carbon::createFromFormat('H:i:s', $job->job_end_time)->format('H:i A');
                    $dataObj['job_location']     = ApplicationStatusHelper::getOnlyArea($job->address_id);
                    $dataObj['job_salary']       = $job->job_salary;
                    array_push($data,$dataObj);
                }
            }
        } elseif ($status == 2) {
            foreach ($paginateData['data'] as $key => $application) {
                $job = Job::with('applications')->whereNotIn('job_status', [1, 3])->find($application->job_id);
                if ($job) {
                    $dataObj['id']               = $job->id;
                    $dataObj['job_title']        = $job->job_title;
                    $dataObj['job_category']     = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
                    $dataObj['job_date']         = Carbon::createFromFormat('Y-m-d', $job->job_date)->format('d-m-Y');
                    $dataObj['application_id']   = $application->id;
                    $dataObj['job_start_time']   = Carbon::createFromFormat('H:i:s', $job->job_start_time)->format('H:i A');
                    $dataObj['job_end_time']     = Carbon::createFromFormat('H:i:s', $job->job_end_time)->format('H:i A');
                    $dataObj['job_location']     = ApplicationStatusHelper::getOnlyArea($job->address_id);
                    $dataObj['job_salary']       = $job->job_salary;
                    array_push($data,$dataObj);
                }
            }
        } elseif ($status == 3) {
            foreach ($paginateData['data'] as $key => $application) {
                $job = Job::with('applications', 'invoices')->whereNotIn('job_status', [1, 2])->find($application->job_id);
                if ($job) {
                    $dataObj['application_id']   = $application->id;
                    $dataObj['id']               = $job->id;
                    $dataObj['job_title']        = $job->job_title;
                    $dataObj['job_category']     = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
                    $dataObj['job_date']         = Carbon::createFromFormat('Y-m-d', $job->job_date)->format('d-m-Y');
                    $dataObj['job_start_time']   = Carbon::createFromFormat('H:i:s', $job->job_start_time)->format('H:i A');
                    $dataObj['job_end_time']     = Carbon::createFromFormat('H:i:s', $job->job_end_time)->format('H:i A');
                    $dataObj['job_location']     = ApplicationStatusHelper::getOnlyArea($job->address_id);
                    $dataObj['working_status']   = isset($application->candidate->working_status) ? ApplicationStatusHelper::getAfterWorkingStatusForCandidateByName($application->candidate->working_status) : '';
                    $dataObj['job_salary']       = $job->job_salary;
                    array_push($data,$dataObj);
                }
            }
        }
        if (count($paginateData) > 0) {
            return response()->json([
                'message' => 'All Jobs',
                'Application_status' => ApplicationStatusHelper::getApplicationStatusByName($status),
                'status' => 'OK',
                'code' => 200,
                'data' => $data,
                'curent_page' => $paginateData['current_page'],
                'last_page' => $paginateData['last_page'],
                'is_last_page' => $paginateData['is_last_page'],
            ]);
        } else {
            return response()->json([
                'message' => 'No Jobs found for this status',
                'status' => 'Bad Request',
                'code' => 400,
                'data' => $data
            ]);
        }
    }

    public function genaratetimesheet($id)
    {
        $job = Job::with('applications')->with('timesheets')->find($id);
        if (!$job) {
            return response()->json([
                'message' => 'Job Not Found',
                'status' => 'Bad Request',
                'code' => 400
            ]);
        }
        $timesheet = Timesheet::where(['job_id' => $job->id])->first();

        if ($timesheet) {
            $application = Application::where(['job_id' => $job->id, 'status' => 2])->first();
            $application->status = 3;
            $application->timesheet_id = $timesheet->id;
            $application->save();

            return response()->json([
                'message' => 'Application status updated to 3',
                'status' => 'OK',
                'code' => 200,
                'data' => $application
            ]);
        } else {
            return response()->json([
                'message' => 'No Timesheet found for this job',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function edit(Application $application)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Application $application)
    {
        //
    }


    public function labalCount($id){
        $candidatePaymentCount = Candidate::with('invoices')->where('id',$id)->get();

        $workingStatus = $candidatePaymentCount[0]->working_status;
        if($workingStatus == 1){
            $payment = Invoice::where(['candidate_id'=>$id,'invoice_status'=>1])->sum('invoice_amount');

            return response()->json([
                'message' => 'Payment Due',
                'status' => 'OK',
                'code' => 200,
                'data' => $payment
            ]);
        }else if($workingStatus == 3){
            $payment = Invoice::where(['candidate_id'=>$id,'invoice_status'=>2])->sum('invoice_amount');

            return response()->json([
                'message' => 'Total Paid',
                'status' => 'OK',
                'code' => 200,
                'data' => $payment
            ]);
        }else{
            return response()->json([
                'message' => 'No Payment',
                'status' => 'OK',
                'code' => 200,
                'data' => 0
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $application = Application::with('job')->find($id);
        $job = Job::with('applications')->where('id', $application->job->id)->first();
        if (!$application) {
            return response()->json([
                'message' => 'Application Not Found',
                'status' => 'Bad Request',
                'code' => 400
            ]);
        }
        $ifMultipleApplication = Application::where('job_id', $job->id)->count();
        if ($ifMultipleApplication == 1) {
            $job->job_status = 0;
            $job->save();
        }

        $application->delete();

        return response()->json([
            'message' => 'Application Deleted Successfully',
            'status' => 'OK',
            'code' => 200
        ]);
    }
}
