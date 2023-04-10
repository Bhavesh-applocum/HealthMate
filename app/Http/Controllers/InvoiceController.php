<?php

namespace App\Http\Controllers;

use App\Application;
use App\Candidate;
use App\Client;
use App\Invoice;
use App\Job;
use App\Timesheet;
use Illuminate\Http\Request;

class InvoiceController extends Controller
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
        // $candidate = $request->candidate_id;
        // $job = $request->job_id;
        // $application = $request->application_id;
        // $timesheet = $request->timesheet_id;
        // $client = $request->client_id;

        // $Maincandidate = Candidate::find($candidate);
        // $Mainjob = Job::find($job);
        // $Mainapplication = Application::find($application);
        // $Maintimesheet = Timesheet::find($timesheet);
        // $Mainclient = Client::find($client);

        // if (!$Maincandidate) {
        //     return response()->json([
        //         'message' => 'Candidate not found',
        //         'status' => 'Bad Request',
        //         'code' => 400
        //     ], 400);
        // }

        // if (!$Mainjob) {
        //     return response()->json([
        //         'message' => 'Job not found',
        //         'status' => 'Bad Request',
        //         'code' => 400
        //     ], 400);
        // }

        // if (!$Mainapplication) {
        //     return response()->json([
        //         'message' => 'Application not found',
        //         'status' => 'Bad Request',
        //         'code' => 400
        //     ], 400);
        // }

        // if (!$Maintimesheet) {
        //     return response()->json([
        //         'message' => 'Timesheet not found',
        //         'status' => 'Bad Request',
        //         'code' => 400
        //     ], 400);
        // }

        // if (!$Mainclient) {
        //     return response()->json([
        //         'message' => 'Client not found',
        //         'status' => 'Bad Request',
        //         'code' => 400
        //     ], 400);
        // }

        // $invoice = new Invoice();
        // $invoice->candidate_id = $candidate;
        // $invoice->job_id = $job;
        // $invoice->application_id = $application;
        // $invoice->timesheet_id = $timesheet;
        // $invoice->client_id = $client;
        // $invoice->save();

        // $invoice->ref_no = 'INV-' . ($invoice->id + 1000);
        // $invoice->save();

        // return response()->json([
        //     'message' => 'Invoice created successfully',
        //     'status' => 'OK',
        //     'code' => 200
        // ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
