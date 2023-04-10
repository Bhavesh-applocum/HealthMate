<?php

namespace App\Http\Controllers;

use App\Application;
use App\Candidate;
use App\Job;
use App\Timesheet;
use Illuminate\Http\Request;

class TimesheetController extends Controller
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
        $candidate = $request->candidate_id;
        $job = $request->job_id;
        $application = $request->application_id;

        $Maincandidate = Candidate::find($candidate);
        $Mainjob = Job::find($job);
        $Mainapplication = Application::find($application);


        if (!$Maincandidate) {
            return response()->json([
                'message' => 'Candidate not found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }

        if (!$Mainjob) {
            return response()->json([
                'message' => 'Job not found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }
        if (!$Mainapplication) {
            return response()->json([
                'message' => 'Application not found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }
        $timesheet = new Timesheet();
        $timesheet->application_id = $application;
        $timesheet->candidate_id = $candidate;
        $timesheet->job_id = $job;
        $timesheet->status = 0;

        $timesheet->save();
        $timesheet->ref_no = 'TMS-' . ($timesheet->id + 10000);
        $timesheet->save();

        $application = Application::where(['candidate_id' => $candidate, 'job_id' => $job])->first();
        $application->timesheet_id = $timesheet->id;
        $application->save();
        


        return response()->json([
            'message' => 'Timesheet created successfully',
            'status' => 'OK',
            'code' => 200
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function show(Timesheet $timesheet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $timesheet = Timesheet::with('job','application','candidate')->find($id);
        // dd($timesheet);
        return response()->json([
            'timesheet' => $timesheet,
            'status' => 'OK',
            'code' => 200
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        // $id = $request->timesheet_id;
        $timesheet = Timesheet::with('job','application','candidate')->find($id);
        // dd($timesheet);

        $timesheet->candidate->working_status = 1;
        $timesheet->candidate->save();

        $timesheet->start_time = $request->start_time;
        $timesheet->end_time = $request->end_time;
        $timesheet->break_time = $request->break_time;
        $timesheet->status = 1;

        $timesheet->save();

        $timesheet->job->job_status = 3;
        $timesheet->job->unit = $request->units;
        $timesheet->job->save();
        $timesheet->application->status = 3;
        $timesheet->application->save();
        
        return response()->json([
            'message' => 'Work Completed',
            'status' => 'OK',
            'code' => 200
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Timesheet $timesheet)
    {
        //
    }
}
