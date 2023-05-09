<?php

namespace App\Http\Controllers\Api;

use App\Candidate;
use App\Client;
use App\Http\Controllers\Controller;
use App\Helpers\ApplicationStatusHelper;
use App\Http\Requests\CandidateRequest;
use App\Http\Requests\CandidateUpdateRequest;
use App\Http\Requests\UploadcvRequest;
use App\Mail\LoginAuthMail;
use Dotenv\Store\File\Paths;
use Facade\FlareClient\Stacktrace\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Mail;
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

        if (sizeof($candidate) > 0) {
            return response()->json([
                'message' => 'Candidate already exists with this Email',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }

        $client = Client::where('email', $request->email)->get();

        if (sizeof($client) > 0) {
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

        $otp = rand(1000, 9999);
        $otp_expire = now()->addMinutes(5);
        $candidate->Login_otp = $otp;
        $candidate->Login_otp_expire = $otp_expire;

        $candidate->save();

        Mail::to($candidate->email)->send(new LoginAuthMail($otp));

        return response()->json([
            'message' => 'Candidate created successfully',
            'status' => 'OK',
            'type' => 2,
            'code' => 200,
            'data' => $candidate
        ], 200);
    }
    public function uploadImage(CandidateUpdateRequest $request)
    {

        $id = $request->id;

        $candidate = Candidate::find($id);

        $oldProfile = $candidate->avatar;
        if (file_exists($oldProfile)) {
            unlink($oldProfile);
        }
        if ($request->avatar) {
            $imageName =  time() . '_' . str_replace(" ", '', $request->avatar->getClientOriginalName());
            $candidate->avatar = $request->avatar->move('images', $imageName);
        } else {
            $candidate->avatar = NULL;
        }

        $candidate->save();

        return response()->json([
            'message' => 'Profile Image updated successfully',
            'status' => 'OK',
            'newImage' => $candidate->avatar,
            'code' => 200
        ], 200);
    }

    //upload cv api for candidate with cv file in directory
    public function uploadCv(UploadcvRequest $request)
    {

        $id = $request->id;

        $candidate = Candidate::find($id);

        $oldCv = $candidate->cv;
        if (file_exists($oldCv)) {
            unlink($oldCv);
        }
        if ($request->cv) {
            $cvName =  time() . '_' . str_replace(" ", '', $request->cv->getClientOriginalName());
            $candidate->cv = $request->cv->move('cv', $cvName);
        } else {
            $candidate->cv = NULL;
        }

        $candidate->save();

        return response()->json([
            'message' => 'CV uploaded successfully',
            'status' => 'OK',
            'code' => 200
        ], 200);
    }

    public function profileedit(CandidateUpdateRequest $request)
    {

        $id = $request->id;

        $candidate = Candidate::find($id);

        if ($candidate == null) {
            return response()->json([
                'message' => 'Candidate not found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }

        $candidate->first_name = $request->first_name;
        $candidate->last_name = $request->last_name;
        $candidate->gender = $request->gender;
        $candidate->email = $request->email;
        $candidate->phone = $request->phone;
        $candidate->updated_at = now();

        // create unique name for image 

        $candidate->save();

        return response()->json([
            'message' => 'Profile updated successfully',
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
        $data = new \stdClass();

        if ($candidate != null) {
            $data->avatar = $candidate->avatar;
            $data->first_name = $candidate->first_name;
            $data->last_name = $candidate->last_name;
            $data->email = $candidate->email;
            $data->phone = $candidate->phone;
            $data->gender = $candidate->gender;

            return response()->json([
                'message' => 'Candidate found',
                'status' => 'OK',
                'code' => 200,
                'data' => $data
            ], 200);
        } else {
            return response()->json([
                'message' => 'Candidate not found',
                'status' => 'Bad Request',
                'code' => 400
            ], 400);
        }
    }

    //edit role api and as per ther role send multiple skills using applicationstatus helper 
    public function editRole($id)
    {
        $candidate = Candidate::find($id);
        $candidateRoleKey = $candidate->role;
        $allRoles = config('constant.job_Category for 2');
        $candidateRole = ApplicationStatusHelper::getCandidateCategoryByName($candidate->role);
        $skills = ApplicationStatusHelper::getRoleSkills($candidateRoleKey);
        return response()->json([
            'message' => 'Candidate found',
            'status' => 'OK',
            'code' => 200,
            'AllRole' => $allRoles,
            'Role' => $candidateRole,
            'data' => $skills
        ], 200);
    }

    public function updateRole(Request $request, $id){
        $candidate = Candidate::find($id);
        $candidate->role = $request->role;
        $candidate->save();
        return response()->json([
            'message' => 'Candidate role updated successfully',
            'status' => 'OK',
            'code' => 200,
            'data' => $candidate
        ], 200);
    }

    //get on tap change skills api for edit role api
    public function getSkills($role)
    {
        $skills = ApplicationStatusHelper::getRoleSkills($role);
        return response()->json([
            'message' => 'Candidate found',
            'status' => 'OK',
            'code' => 200,
            'data' => $skills
        ], 200);
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
