<?php

namespace App\Http\Controllers\Admin;

use App\Address;
use App\Helper\DataTableHelper;
use App\Helpers\ApplicationStatusHelper;
use App\Helpers\GeneralHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Job;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = Job::select('*');
            return DataTableHelper::getJobTable($data);
        }
        return view('admin.jobs.index');    
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $job = Job::with('client')->findOrFail($id);
        $address = Address::with('job')->where(['id'=>$job->address_id, 'client_id'=>$job->client_id])->first();
        $data = [];

        $data['id'] = $job->ref_no;
        $data['title'] = $job->job_title;
        $data['description'] = $job->job_description;
        $data['status'] = ApplicationStatusHelper::getJobStatusByName($job->job_status);
        $data['client_avatar'] = $job->client->avatar;
        $data['client'] = $job->client->practice_name;
        $data['job_category'] = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
        $data['job_date'] = Carbon::createFromFormat('Y-m-d' , $job->job_date)->format('d-m-Y');
        $data['job_start_time'] = Carbon::createFromFormat('H:i:s', $job->job_start_time)->format('H:i A');
        $data['job_end_time'] = Carbon::createFromFormat('H:i:s', $job->job_end_time)->format('H:i A');
        $data['salary'] = '₹'. $job->job_salary;
        $data['address'] = $address->address;
        $data['area'] = $address->area;
        $data['post_code'] = $address->post_code;
        $data['break'] = Carbon::createFromFormat('H:i:s',$job->break_time)->format('i:s A');
        $data['parking'] = GeneralHelper::getParkingInfo($job->parking);
        $data['created_at'] = Carbon::createFromFormat('Y-m-d H:i:s', $job->created_at)->format('d-m-Y H:i:s');

        return view('admin.jobs.view', [
            'data' => $data
        ]);
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
        $job =  Job::findOrFail($id);

        if($job->delete()){
            $message = config('params.msg_success'). 'Job Deleted Successfully!' .config('params.msg_end');
            Session::flash('message', $message);
        }else {
            $message = config('params.msg_error'). 'Job Not Deleted!' .config('params.msg_end');
            Session::flash('message', $message);
        }
        return response()->json([
            'success' => true,
            'message' => $message
        ],200);
    }
}
