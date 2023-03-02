<?php

namespace App\Http\Controllers;

use App\Candidate;
use App\Client;
use App\Http\Requests\ClientRequest;
use App\Http\Requests\ClientUpdateRequest;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Response as HttpResponse;
use Symfony\Component\HttpFoundation\Test\Constraint\ResponseStatusCodeSame;

class ClientController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientRequest $request, Candidate $candidate)
    {

        $client = Client::where('email', $request->email)->get();

        if(sizeof($client) > 0) {
            return response()->json([
                'message' => "Client already exists with this Email",
                'status' => 'Bad Request',
                'code' =>  400
            ], 400);
        }

        $candidate = Candidate::where('email', $request->email)->get();

        if(sizeof($candidate) > 0) {
            return response()->json([
                'message'=> 'Candidate already exists with this Email',
                'code' => 400
            ],400);
        }

        $client = new Client;

        $client->practice_name = $request->practice_name;
        $client->email = $request->email;
        $client->phone = $request->phone;
        $client->address = $request->address;
        $client->password = Hash::make($request->password);
        $client->created_at = now();
        $client->updated_at = now();

        $client->save();

        return $client;
    }

    public function profileedit(ClientUpdateRequest $request){

        $id = $request->id;

        $client = Client::find($id);

        if($client == null){
            return response()->json([
                'message' => 'Client not found',
                'status' => 'Bad Request',
                'code' => 400
            ],400);
        }

        $oldProfile = $client->avatar;
        if($oldProfile != null){
            unlink($oldProfile);
        }

        $client->practice_name = $request->practice_name;
        $client->phone = $request->phone;
        $client->address = $request->address;
        $client->password = Hash::make($request->password);

        $client->avatar = $request->avatar->move('images', $request->avatar->getClientOriginalName());

        $client->save();

        return response()->json([
            'message' => 'Client updated successfully',
            'status' => 'OK',
            'code' => 200
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        //
    }
}
