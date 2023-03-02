<?php

namespace App\Http\Controllers;

use App\Application;
use App\Candidate;
use App\Job;
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

       if(!$candidate) {
           return response()->json([
               'message' => 'Candidate not found',
               'status' => 'Bad Request',
               'code' => 400
           ], 400);
       }

       $job = Job::find($request->job_id);

         if(!$job) {
              return response()->json([
                'message' => 'Job not found',
                'status' => 'Bad Request',
                'code' => 400
              ], 400);
            }

         $application = new Application;

            $application->candidate_id = $candidate->id;
            $application->job_id = $job->id;

            $application->save();

            return response()->json([
                'message' => 'Application successfully created',
                'status' => 'OK',
                'code' => 200
            ], 200);
    }

    public function approveApplication(Request $request)
    {
        $candidateID = $request->candidate_id;
        $jobID = $request->job_id;
        $applicationID = $request->application_id;
        // $application = Application::find($request->application_id);
        $application = Application::where('candidate_id', $candidateID)->where('job_id', $jobID)->where('id', $applicationID)->first();

        if(!$application) {
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
        $candidateID = $request->candidate_id;
        $jobID = $request->job_id;
        $applicationID = $request->application_id;
        
        $application = Application::where('candidate_id', $candidateID)->where('job_id', $jobID)->where('id', $applicationID)->first();

        if(!$application) {
            return response()->json([
                'message' => 'Application not found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }
        
        $application->status = 0;

        $application->save();

        return response()->json([
            'message' => 'Application successfully rejected',
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
    public function show(Application $application)
    {
        //
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
    public function destroy(Application $application)
    {
        //
    }
}
