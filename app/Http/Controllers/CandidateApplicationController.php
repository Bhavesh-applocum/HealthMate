<?php

namespace App\Http\Controllers;

use App\Application;
use App\Candidate;
use App\Helpers\ApplicationStatusHelper;
use App\Job;
use App\Timesheet;
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

        $job = Job::find($request->job_id);

        if (!$job) {
            return response()->json([
                'message' => 'Job not found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }

        if ($job->job_start_date < now() && $job->job_start_time <= now()) {
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

        $application->save();

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
        // $candidateApplication = Application::where('candidate_id', $candidateID)->get();

        // foreach($candidateApplication as $application) {
        //     $job = Job::with('applications')->find($application->job_id);
        //     dd($job);
        // }

        // // return response()->json([
        // //     'message' => 'All Jobs',
        // //     'status' => 'OK',
        // //     'code' => 200,
        // //     'data' => $alljobs
        // // ]);

        // $job = Job::with('applications')->find($id);
        // // $application = Application::find($id);
        // // $application->status;
        // // dd($application->status);

        // foreach($job->applications as $application) {
        //     if($application->status == 1){
        //         return response()->json([
        //             'message' => 'Applied Jobs',
        //             'status' => 'OK',
        //             'code' => 200,
        //             'data' => $application
        //         ]);
        //         dd($application);
        //     }
        // }
    }

    public function showstautsjob(Request $request)
    {
        $candidateId = $request->candidate_id;
        $status = $request->status;

        $candidateApplication = Application::where(['candidate_id' => $candidateId, 'status' => $status])->get();
        if (!$candidateApplication) {
            return response()->json([
                'message' => 'Candidate Not Found',
                'status' => 'Bad Request',
                'code' => 400
            ]);
        }
        $data = [];
        if ($status == 1) {
            foreach ($candidateApplication as $key => $application) {

                $isBookedExistsForThisJob = Application::where(['job_id' => $application->job_id, 'status' => 2])->exists();
                $isWorkedExistsForThisJob = Application::where(['job_id' => $application->job_id, 'status' => 3])->exists();


                if (!$isBookedExistsForThisJob && !$isWorkedExistsForThisJob) {
                    $job = Job::with('applications')->find($application->job_id);
                    $data[$key]['job_id']           = $job->id;
                    $data[$key]['job_title']        = $job->job_title;
                    $data[$key]['job_description']  = $job->job_description;
                    $data[$key]['job_start_date']   = $job->job_start_date;
                    $data[$key]['job_start_time']   = $job->job_start_time;
                    $data[$key]['job_end_date']     = $job->job_end_date;
                    $data[$key]['job_end_time']     = $job->job_end_time;
                    $data[$key]['job_location']     = $job->job_location;
                    $data[$key]['job_status']       = $job->job_status;
                    $data[$key]['job_created_at']   = $job->created_at;
                }
            }
        } else if ($status == 2) {
            foreach ($candidateApplication as $key => $application) {

                // $isBookedExistsForThisJob = Application::where(['job_id' => $application->job_id, 'status' => 2])->exists();
                $isWorkedExistsForThisJob = Application::where(['job_id' => $application->job_id, 'status' => 3])->exists();
                $isAppliedForThisJob = Application::where(['job_id' => $application->job_id, 'status' => 1])->exists();

                if (!$isWorkedExistsForThisJob && !$isAppliedForThisJob) {
                    $job = Job::with('applications')->find($application->job_id);
                    $data[$key]['job_id']           = $job->id;
                    $data[$key]['job_title']        = $job->job_title;
                    $data[$key]['job_description']  = $job->job_description;
                    $data[$key]['job_start_date']   = $job->job_start_date;
                    $data[$key]['job_start_time']   = $job->job_start_time;
                    $data[$key]['job_end_date']     = $job->job_end_date;
                    $data[$key]['job_end_time']     = $job->job_end_time;
                    $data[$key]['job_location']     = $job->job_location;
                    // $data[$key]['job_status']       = $job->job_status;
                    $data[$key]['job_created_at']   = $job->created_at;
                }
            }
        } else if ($status == 3) {
            foreach ($candidateApplication as $key => $application) {

                $isBookedExistsForThisJob = Application::where(['job_id' => $application->job_id, 'status' => 2])->get();
                $isAppliedForThisJob = Application::where(['job_id' => $application->job_id, 'status' => 1])->get();

                if (!$isBookedExistsForThisJob && !$isAppliedForThisJob) {
                    $job = Job::with('applications')->find($application->job_id);
                    $data[$key]['job_id']           = $job->id;
                    $data[$key]['job_title']        = $job->job_title;
                    $data[$key]['job_description']  = $job->job_description;
                    $data[$key]['job_start_date']   = $job->job_start_date;
                    $data[$key]['job_start_time']   = $job->job_start_time;
                    $data[$key]['job_end_date']     = $job->job_end_date;
                    $data[$key]['job_end_time']     = $job->job_end_time;
                    $data[$key]['job_location']     = $job->job_location;
                    $data[$key]['job_status']       = $job->job_status;
                    // $data[$key]['job_created_at']   = $job->created_at;
                }
            }
        }
        // dd($data);
        if (count($candidateApplication) > 0) {
            // if ($status == 2)
            //     return response()->json([
            //         'message' => 'Applied Jobs',
            //         'Application_status' => ApplicationStatusHelper::getApplicationStatusByName($status),
            //         'Rejected_reason' => $application->reject_reason,
            //         'status' => 'OK',
            //         'code' => 200,
            //         'data' => $data
            //     ]);
            return response()->json([
                'message' => 'All Jobs',
                'Application_status' => ApplicationStatusHelper::getApplicationStatusByName($status),
                'status' => 'OK',
                'code' => 200,
                'data' => $data
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
        $checkdetail = Timesheet::where(['job_id' => $job->id])->first();

        if ($checkdetail) {
            // update application status to 3
            $application = Application::where(['job_id' => $job->id, 'status' => 2])->first();
            $application->status = 3;
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $application = Application::find($id);

        if (!$application) {
            return response()->json([
                'message' => 'Application Not Found',
                'status' => 'Bad Request',
                'code' => 400
            ]);
        }

        $application->delete();

        return response()->json([
            'message' => 'Application Deleted Successfully',
            'status' => 'OK',
            'code' => 200
        ]);
    }
}
