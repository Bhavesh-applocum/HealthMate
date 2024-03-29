<?php

namespace App\Http\Controllers\Admin;

use App\Address;
use App\Client;
use App\Helper\DataTableHelper;
use App\Helpers\ApplicationStatusHelper;
use App\Helpers\GeneralHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCreateJobRequest;
use App\Job;
use Carbon\Carbon;
use DateTime;
use Facade\FlareClient\Time\Time;
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
        $client = Client::all();
        $data['allCategory'] = config('constant.job_Category');
        $data['parking'] = config('constant.parking');
        $data['AllClient'] = $client;
        return view('admin.jobs.create',[
            'data' => $data
        ]);
    }

    public function getClientDetails(Request $request, $id)
    {
        $client = Client::with('address')->findOrFail($id);
        // dd($client);
        $address = Address::where('id', $client->address_id)->first();
        // dd($address);
        $addData = Address::where('client_id',$id)->get();
        foreach ($addData as $key => $value) {
            $Alladdress[$key]['id'] = $value->id;
            $Alladdress[$key]['address'] = $value->address;
            $Alladdress[$key]['url'] = route('admin.jobs.area.edit', $value['id']) ;
        }
        $data = [];
        $data['client_avatar'] = $client->avatar;
        $data['client_email'] = $client->email;
        $data['client_phone'] = $client->phone;
        $data['client_address_id'] = $client->address_id;
        $data['client_address'] = $address->address;
        $data['client_area'] = $address->area;
        $data['client_post_code'] = $address->post_code;
        $data['AllAddress']  = $Alladdress;
        return response()->json([
            'data' => $data
        ], 200);

        // $simpleResponse = response([
        //     'address' => $Alladdress
        // ]);

        // if($request->ajax()){
        //     return $jsonResponse;
        // }   
        // return $simpleResponse;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminCreateJobRequest $request)
    {
        $startTime = Carbon::createFromFormat('H:i',  $request->job_start_time); // 06:59
        $endTime = Carbon::createFromFormat('H:i', $request->job_end_time); // 18:48
        $breakTime = Carbon::createFromFormat('H:i', $request->break)->format('i'); // 00.50
        $diff = $endTime->diffInMinutes($startTime); // 709 (Min) 
        $difference = $diff - $breakTime; // 659
        $hours = (int)floor($difference/60);
        $minutes = (int)($difference - floor($difference / 60)* 60);
        $d = $hours.'.'.$minutes;
        $unitDouble = (doubleval($d));

        $job = new Job();
        $job->job_title = $request->title;
        $job->job_description = $request->description;
        $job->job_category = $request->role;
        $job->job_date = $request->jobdate;
        $job->job_start_time = Carbon::createFromFormat('H:i', $request->job_start_time)->format('H:i:s');
        $job->job_end_time = Carbon::createFromFormat('H:i', $request->job_end_time)->format('H:i:s');
        $job->job_salary = $request->salary;
        $job->unit = $unitDouble;
        $job->client_id = $request->client_id;
        $job->address_id = $request->address_id;
        $job->parking = $request->parking;
        $job->break_time = $request->break;
        $job->save();

        $job->ref_no = 'CON-' . ($job->id + 10000);
        $job->save();

        // message 
        $message = config('params.msg_success'). 'Job created successfully' .config('params.msg_end');
        Session::flash('message', $message);
        return redirect()->route('admin.jobs.index');
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
        $startTime = Carbon::createFromFormat('H:i:s', $job->job_start_time)->format('H:i A');
        $endTime = Carbon::createFromFormat('H:i:s', $job->job_end_time)->format('H:i A');

        $data['id'] = $job->id;
        $data['title'] = $job->job_title;
        $data['description'] = $job->job_description;
        $data['status'] = ApplicationStatusHelper::getJobStatusByName($job->job_status);
        $data['client_avatar'] = $job->client->avatar;
        $data['client'] = $job->client->practice_name;
        $data['client_email'] = $job->client->email;
        $data['client_phone'] = $job->client->phone;
        $data['job_category'] = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
        $data['job_date'] = Carbon::createFromFormat('Y-m-d', $job->job_date)->format('d/m/Y');
        $data['job_time'] = $startTime. ' - ' .$endTime;
        $data['salary'] = $job->job_salary;
        $data['address'] = $address->address . '' .$address->area. '-' .$address->post_code;
        $data['break'] = Carbon::createFromFormat('H:i:s',$job->break_time)->format('H:i');
        $data['parking'] = GeneralHelper::getParkingInfo($job->parking);
        $data['created_at'] = Carbon::createFromFormat('Y-m-d H:i:s', $job->created_at)->format('d/m/Y - H:i:s A');

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
        $job = Job::with('client')->findOrFail($id);
        $address = Address::with('job')->where(['id'=>$job->address_id, 'client_id'=>$job->client_id])->first();
        $allAddress = Address::where('client_id',$job->client_id)->get();
        // dd($allAddress);
        $data = [];
        $startTime = Carbon::createFromFormat('H:i:s', $job->job_start_time)->format('H:i A');
        $endTime = Carbon::createFromFormat('H:i:s', $job->job_end_time)->format('H:i');

        $data['id'] = $job->id;
        $data['title'] = $job->job_title;
        $data['description'] = $job->job_description;
        $data['status'] = ApplicationStatusHelper::getJobStatusByName($job->job_status);
        $data['client_avatar'] = $job->client->avatar;
        $data['client'] = $job->client->practice_name;
        $data['client_email'] = $job->client->email;
        $data['client_phone'] = $job->client->phone;
        $data['job_category'] = ApplicationStatusHelper::getJobCategoryByName($job->job_category);
        $data['AllCategories'] = config('constant.job_Category');
        $data['job_date'] = Carbon::createFromFormat('Y-m-d', $job->job_date)->format('d-m-Y');
        $data['job_start_time'] = $startTime;
        $data['job_end_time'] = $endTime;
        $data['salary'] = $job->job_salary;
        $data['AllClientAddress'] = $allAddress;
        $data['address'] = $address->address;
        $data['area'] = $address->area;
        $data['post_code'] = $address->post_code;
        $data['break'] = Carbon::createFromFormat('H:i:s',$job->break_time)->format('H:i');
        $data['parking'] = GeneralHelper::getParkingInfo($job->parking);
        $data['AllParking'] = config('constant.parking');
        $data['created_at'] = Carbon::createFromFormat('Y-m-d H:i:s', $job->created_at)->format('d/m/Y - H:i:s A');

        // dd($data);
        return view('admin.jobs.edit', [
            'data' => $data
        ]);
    }

    public function editAjaxArea($id){
        $address = Address::where('id',$id)->first();
        $data = [];
        $data['area'] = $address->area;
        $data['post_code'] = $address->post_code;
        return response()->json([
            'data' => $data
        ],200);
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
        // dd($request->all());
        $job = Job::findOrFail($id);
        $job->job_title = $request->title;
        $job->job_description = $request->description;
        $job->job_category = $request->role;
        $job->job_date = $request->jobdate;
        $job->job_start_time = $request->job_start_time;
        $job->job_end_time = $request->job_end_time;
        $job->job_salary = $request->salary;
        $job->address_id = $request->address;
        $job->break_time = $request->break;
        $job->parking = $request->parking;
        $job->save();

        if($job){
            $message = config('params.msg_success'). 'Job Updated Successfully!' .config('params.msg_end');
            Session::flash('message', $message);
            return redirect()->route('admin.jobs.index');
        }else {
            $message = config('params.msg_error'). 'Job Not Updated!' .config('params.msg_end');
            Session::flash('message', $message);
            return redirect()->route('admin.jobs.index');
        }
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
