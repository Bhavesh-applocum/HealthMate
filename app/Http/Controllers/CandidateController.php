<?php

namespace App\Http\Controllers;

use App\Candidate;
use App\Client;
use App\Helpers\ApplicationStatusHelper;
use App\Http\Requests\CandidateRequest;
use App\Http\Requests\CandidateUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpKernel\EventListener\ResponseListener;

class CandidateController extends Controller
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
    public function store(CandidateRequest $request, Client $client)
    {

        $candidate = Candidate::where('email', $request->email)->get();

        if(sizeof($candidate) > 0) {
            return response()->json([
                'message' => 'Candidate already exists with this Email',
                'status' => 'Bad Request',
                'code' => 400   
            ], 400);
        }
        
        $client = Client::where('email', $request->email)->get();

        if(sizeof($client) > 0) {
            return response()->json([
                'message' => "Client already exists with this Email",
                'status' => 'Bad Request',
                'code' =>  400
            ], 400);
        }
        $allJobCategory = config('constant.job_Category');

        $candidate = new Candidate;

        $jobCategory = '';
        for ($i = 1; $i <= count($allJobCategory); $i++) {
            if ($request->role == $i) {
                $jobCategory = $allJobCategory[$i];
            }
        };

        $candidate->first_name = $request->first_name;
        $candidate->last_name = $request->last_name;
        $candidate->email = $request->email;
        $candidate->phone = $request->phone;
        $candidate->role = $request->role;
        $candidate->gender = $request->gender;
        $candidate->password = Hash::make($request->password);
        $candidate->created_at = now();
        $candidate->updated_at = now();

        $candidate->save();

        return $candidate;
    }

    public function profileedit(CandidateUpdateRequest $request){

        $id = $request->id;

        $candidate = Candidate::find($id);

        if($candidate == null){
            return response()->json([
                'message' => 'Candidate not found',
                'status' => 'Bad Request',
                'code' => 400   
            ], 400);
        }

        $oldProfile = $candidate->avatar;
        if($oldProfile != null){
            unlink($oldProfile);
        }

        $candidate->first_name = $request->first_name;
        $candidate->last_name = $request->last_name;
        $candidate->phone = $request->phone;
        $candidate->password = Hash::make($request->password);
        $candidate->updated_at = now();

        $candidate->avatar = $request->avatar->move('images', $request->avatar->getClientOriginalName());


        $candidate->save();

        return response()->json([
            'message' => 'Candidate updated successfully',
            'status' => 'OK',
            'code' => 200   
        ], 200);
        return $candidate;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $candidate = Candidate::find($id);

        if($candidate){
            $candidate->role = ApplicationStatusHelper::getCandidateCategoryByName($candidate->role);
        return response()->json([
            'message' => 'Candidate found',
            'status' => 'OK',
            'code' => 200,
            'data' => $candidate
        ], 200);
    }else{
        return response()->json([
            'message' => 'Candidate not found',
            'status' => 'Bad Request',
            'code' => 400   
        ], 400);
    }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function edit(Candidate $candidate)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Candidate $candidate)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Candidate  $candidate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Candidate $candidate)
    {
        //
    }
}
