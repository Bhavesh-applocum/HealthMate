<?php

namespace App\Http\Controllers\Api;

use App\Address;
use App\Client;
use App\Http\Controllers\Controller;
use App\Job;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $address = Address::with('client')->where('client_id', $id)->orderBy('id','desc')->get();
        if (sizeof($address) > 0){
        return response()->json([
            'message' => 'Address found',
            'address' => $address,
            'code'    => 200
        ], 200);
        }else{
            return response()->json([
                'message' => 'No address found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }
    }

    public function isDefault(Request $request){
        $id = $request->client_id;
        $client = Client::with('address')->where('id', $id)->get();

        if ($request->is_default == true){
            $client = Client::findOrFail($id);
            $client->address_id = $request->address_id;
            $client->save();
            }
        return response()->json([
            'message' => 'Address set as default',
            'status' => 'success',
            'code' => 200
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
    public function store(Request $request)
    {
        $id = $request->client_id;
        $address = new Address();
        $address->client_id = $id;
        $address->address = $request->address;
        $address->area = $request->area;
        $address->post_code = $request->post_code;
        $address->save();
        
        if($request->is_default != 'false'){
            $client = Client::findOrFail($id);
            $client->address_id = $address->id;
            $client->save();
        }

        return response()->json([
            'status' => 'success',
            'code'   => 200,
            'message' => 'Address created successfully',
            'address' => $address
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Address $address)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $address = Address::with('client')->findOrFail($id);
        return response()->json([
            'message' => 'Address found',
            'code'    => 200,
            'address' => $address
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $address = $request->address_id;
        $address = Address::findOrFail($address);

        $address->address = $request->address;
        $address->area = $request->area;
        $address->post_code = $request->post_code;

        $address->save();

        return response()->json([
            'message' => 'Address updated successfully',
            'code'    => 200,
            'address' => $address
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy($address)
    {
        $address = Address::findOrFail($address);
        $deleteAddressId = $address->id;

        $address->delete();
        
        $client_id = $address->client_id;
        $client = Client::findOrFail($client_id);
        $jobs = Job::where('client_id',$client_id)->get();
        $totalAddressCount = Address::where('client_id',$client_id)->count();
        $isDeletedDefault = $client->address_id == $deleteAddressId;
        $jobHasDeletedAddress = Job::where('address_id',$deleteAddressId)->count();
        
        if($totalAddressCount != 0 && $isDeletedDefault){
            $lastAddress = Address::where('client_id',$client_id)->first();
            $client->address_id = $lastAddress->id;
            $client->save();
        }
        if($jobHasDeletedAddress != 0){
            $newJobAddress = $client->address_id;
            foreach($jobs as $job){
                $job->address_id = $newJobAddress;
                $job->save();
            }   
        }
        
        return response()->json([
            'message' => 'Address deleted successfully',
            'code'    => 200,
            'address' => $address
        ], 200);
    }
}
