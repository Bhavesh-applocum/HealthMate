<?php

namespace App\Http\Controllers;

use App\Address;
use App\Client;
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
        $address = Address::where('client_id', $id)->all();

        return response()->json([
            'address' => $address
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

        if($request->is_default){
            $client = Client::findOrFail($id);
            $client->address_id = $address->id;
            $client->save();
        }

        return response()->json([
            'message' => 'Address created successfully',
            'address' => $address
        ], 201);
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
    public function edit(Address $address)
    {
        $address = Address::findOrFail($address);
        return response()->json([
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
    public function update(Request $request, Address $address)
    {
        $address = Address::findOrFail($address);

        $address->address = $request->address;
        $address->area = $request->area;
        $address->post_code = $request->post_code;

        $address->save();

        if($request->is_default)
        {
            $client = Client::findOrFail($request->client_id);
            $client->address_id = $address->id;
            $client->save();
        }

        return response()->json([
            'message' => 'Address updated successfully',
            'address' => $address
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy(Address $address)
    {
        $address = Address::findOrFail($address);
        $deleteAddressId = $address->id;
        $client_id = $address->client_id;
        $address->delete();
        $totalAddressCount = Address::where('client_id',$client_id)->count();
        $client = Client::findOrFail($client_id);
        $isDeletedDefault = $client->address_id == $deleteAddressId;
        if($totalAddressCount != 0 && !$isDeletedDefault){
            $lastAddress = Address::where('client_id',$client_id)->first();
            $client->address_id = $lastAddress->id;
            $client->save();
        }

        return response()->json([
            'message' => 'Address deleted successfully',
            'address' => $address
        ], 200);
    }
}
