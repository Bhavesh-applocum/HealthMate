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
        $candidate = Candidate::find($request->candidate_id);
        $job = Job::find($request->job_id);

        if (!$candidate) {
            return response()->json([
                'message' => 'Candidate not found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }

        if (!$job) {
            return response()->json([
                'message' => 'Job not found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }

        $timesheet = new Timesheet();
        $timesheet->candidate_id = $candidate->id;
        $timesheet->job_id = $job->id;
        $timesheet->start_time = $request->start_time;
        $timesheet->end_time = $request->end_time;
        $timesheet->break_time = $request->break_time;

        $timesheet->save();

        $timesheet->ref_no = 'TMS-' . ($timesheet->id + 10000);
        $timesheet->save();

        $application = Application::where(['candidate_id' => $candidate->id, 'job_id' => $job->id])->first();
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
    public function edit(Timesheet $timesheet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Timesheet  $timesheet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Timesheet $timesheet)
    {
        //
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
