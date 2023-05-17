<?php

namespace App\Http\Controllers\Admin;

use App\Address;
use App\Candidate;
use App\Client;
use App\Helper\DataTableHelper;
use App\Helpers\ApplicationStatusHelper;
use App\Helpers\GeneralHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCandidateCreateRequest;
use App\Http\Requests\AdminCandidateUpdateRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $data = Candidate::select('*');
            return DataTableHelper::getCandidateTable($data);
        }
        return view('admin.candidates.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = config('constant.job_Category');
        return view('admin.candidates.create', [
            'data' => $data,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminCandidateCreateRequest $request)
    {
        $candidate = new Candidate;
        if ($request->hasFile('avatar')) {
            $avatarName = time() . '_' . str_replace(" ", '', $request->avatar->getClientOriginalName());
            $candidate->avatar = $request->avatar->move('images', $avatarName);
        } else {
            $candidate->avatar = NULL;
        }
        $candidate->first_name = $request->first_name;
        $candidate->last_name = $request->last_name;
        $candidate->email = $request->email;
        $candidate->phone = $request->phone;
        $candidate->gender = $request->gender;
        $candidate->role = $request->role;
        $candidate->password = Hash::make($request->password);
        $candidate->save();
        return redirect()->route('admin.candidates.index')->with('success', 'Candidate Successfully Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $candidate = Candidate::find($id);

        $Avatar = File::exists(public_path($candidate->avatar));
        if (!$Avatar) {
            $candidate->avatar = Null;
            $candidate->save();
        }
        // dd($candidateAddress);
        $data = [];

        $data['edit_link'] = route('admin.candidates.edit', ['id' => $candidate->id]);
        $data['avatar'] = $candidate->avatar;
        $data['name'] = $candidate->first_name. '' .$candidate->last_name;
        $data['gender'] = GeneralHelper::GenderFullName($candidate->gender);
        $data['role'] = ApplicationStatusHelper::getCandidateCategoryByName($candidate->role);
        $data['email'] = $candidate->email;
        $data['phone'] = $candidate->phone;
        // dd($data);
        return response()->json([
            'data' => $data,
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $candidate = Candidate::find($id);

        $data = [];

        $data['id'] = $candidate->id;
        $data['avatar'] = $candidate->avatar;
        $data['first_name'] = $candidate->first_name; 
        $data['last_name'] = $candidate->last_name;
        $data['gender'] = GeneralHelper::GenderFullName($candidate->gender);
        $data['role'] = ApplicationStatusHelper::getCandidateCategoryByName($candidate->role);
        $data['email'] = $candidate->email;
        $data['phone'] = $candidate->phone;
        $data['AllRole'] = config('constant.job_Category');

        // dd($data);

        return view('admin.candidates.edit', [
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AdminCandidateUpdateRequest $request, $id)
    {
        // dd($request->avatar);
        $candidate = Candidate::find($id);
        if ($request->avatar) {
            $oldProfile = $candidate->avatar;
            if (file_exists($oldProfile)) {
                unlink($oldProfile);
            }
            $avatarName = time() . '_' . str_replace(" ", '', $request->avatar->getClientOriginalName());
            $candidate->avatar = $request->avatar->move('images', $avatarName);
        }
        $candidate->first_name = $request->first_name;
        $candidate->last_name = $request->last_name;
        $candidate->gender = $request->gender;
        $candidate->role = $request->role;
        $candidate->email = $request->email;
        $candidate->phone = $request->phone;
        $candidate->save();
        return redirect()->route('admin.candidates.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $candidate = Candidate::find($id);
        if (file_exists($candidate->avatar)) {
            unlink($candidate->avatar);
        }
        $candidate->delete();
        if ($candidate->delete()) {
            $message =  config('params.msg_success') . 'Client Deleted Sucessfully' . config('params.msg_end');
            Session::flash('message', $message);
        } else {
            $message = config('params.msg_error') . 'Client Not Deleted' . config('params.msg_end');
            Session::flash('message', $message);
        }
        return response()->json([
            'success' => true,
        ], 200);
    }
}
