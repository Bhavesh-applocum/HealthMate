<?php

namespace App\Http\Controllers\Admin;

use App\Address;
use App\Candidate;
use App\Client;
use App\Helper\DataTableHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminClientRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Client::with('address')->select('*');
            // dd($data);
            return DataTableHelper::getClientTable($data);
        }
        return view('admin.clients.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AdminClientRequest $request)
    {
        $client = new Client;
        if ($request->hasFile('avatar')) {
            $avatarName = time() . '_' . str_replace(" ", '', $request->avatar->getClientOriginalName());
            $client->avatar = $request->avatar->move('images', $avatarName);
        } else {
            $client->avatar = NULL;
        }
        $client->practice_name = $request->practice_name;
        $client->email = $request->email;
        $client->phone = $request->phone;
        $client->password = Hash::make($request->password);
        $client->save();

        $address = new Address;
        $address->client_id = $client->id;
        $address->address = $request->address;
        $address->area = $request->area;
        $address->post_code = $request->post_code;
        $address->save();

        $client->address_id = $address->id;
        $client->save();

        $message = config('params.msg_success') . 'Client Created Successfully!' . config('params.msg_end');
        $request->session()->flash('message', $message);
        return redirect()->route('admin.clients.index');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $client = Client::find($id);
        $address = Address::where('client_id', $client->id)->get();
        $clientAddress = Address::where('id', $client->address_id)->first();

        // $Address = isset($clientAddress->address) ? $clientAddress->address : '';
        // $Area = isset($clientAddress->area) ? $clientAddress->area : '';
        // $PostCode = isset($clientAddress->post_code) ? $clientAddress->post_code : '';
        $FullAddress  = '--';
        if (isset($clientAddress->address) && isset($clientAddress->area) && isset($clientAddress->post_code)) {
            $FullAddress = $clientAddress->address . ',' . $clientAddress->area . '-' . $clientAddress->post_code;
        }

        $Avatar = File::exists(public_path($client->avatar));
        if (!$Avatar) {
            $client->avatar = Null;
            $client->save();
        }
        // dd($clientAddress);
        $data = [];

        $data['edit_link'] = route('admin.clients.edit', ['id' => $client->id]);
        $data['avatar'] = $client->avatar;
        $data['name'] = $client->practice_name;
        $data['email'] = $client->email;
        $data['phone'] = $client->phone;
        $data['address'] = $FullAddress;
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
        $client = Client::find($id);
        $address = Address::where('client_id', $client->id)->get();
        $clientAddress = Address::where('id', $client->address_id)->first();
        // dd($clientAddress);
        $data = [];

        $data['id'] = $client->id;
        $data['avatar'] = $client->avatar;
        $data['practice_name'] = $client->practice_name;
        $data['email'] = $client->email;
        $data['phone'] = $client->phone;
        $data['address'] = isset($clientAddress->address) ? $clientAddress->address : '';
        $data['area'] = isset($clientAddress->area) ? $clientAddress->area : '';
        $data['post_code'] = isset($clientAddress->post_code) ? $clientAddress->post_code : '';

        // dd($data);
        return view('admin.clients.edit', [
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
    public function update(Request $request, $id)
    {
        $client = Client::find($id);
        //update avatar 
        if ($request->avatar) {
            $oldProfile = $client->avatar;
            if (file_exists($oldProfile)) {
                unlink($oldProfile);
            }
            $avatarName = time() . '_' . str_replace(" ", '', $request->avatar->getClientOriginalName());
            $client->avatar = $request->avatar->move('images', $avatarName);
        }
        $client->practice_name = $request->practice_name;
        $client->email = $request->email;
        $client->phone = $request->phone;
        $client->save();

        //cnange address in address table
        $address = Address::where('id', $client->address_id)->first();
        if (!$address) {
            $address = new Address();
            $address->client_id = $client->id;
            $address->address  = $request->address;
            $address->area = $request->area;
            $address->post_code = $request->post_code;
            $address->save();

            $client->address_id = $address->id;
            $client->save();
            return redirect()->route('admin.clients.index');
        }
        $address->address = $request->address;
        $address->area = $request->area;
        $address->post_code = $request->post_code;
        $address->save();
        return redirect()->route('admin.clients.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = Client::findOrFail($id);

        if ($client->delete()) {
            $message =  config('params.msg_success') . 'Client Deleted Sucessfully' . config('params.msg_end');
            Session::flash('message', $message);
        } else {
            $message = config('params.msg_error') . 'Client Not Deleted' . config('params.msg_end');
            Session::flash('message', $message);
        }
        return response()->json([
            'success' => true,
            'message' => $message,
        ], 200);
    }
}
