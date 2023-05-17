<?php

namespace App\Helper;

use App\Address;
use App\Client;
use App\Helpers\ApplicationStatusHelper;
use App\Helpers\GeneralHelper;
use Carbon\Carbon;
use Yajra\DataTables\Facades\DataTables;

use function Clue\StreamFilter\fun;

class DataTableHelper 
{
    public static function getClientTable($data)
    {
        $all_address = Address::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {
                return $row->practice_name;
            })
            ->editColumn('email', function ($row) {
                return $row->email;
            })
            ->editColumn('phone', function ($row) {
                return $row->phone;
            })
            ->editColumn('location', function ($row) {
                $address = Address::where(['client_id'=>$row->id, 'id'=>$row->address_id])->first();
                $address = $address['area'];
                $addressArea = isset($address) ? $address : '--';
                return $addressArea;
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d-m-Y');
            })
            ->addColumn('action', function ($row) {
                return view('partials.client.__action', [
                    'row' => $row
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public static function getCandidateTable($data)
    {
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('name', function ($row) {
                $fullName = $row->first_name. ' ' .$row->last_name;
                return $fullName;
            })
            ->editColumn('gender', function ($row) {
                return GeneralHelper::GenderFullName($row->gender);
            })
            ->editColumn('role', function ($row) {
                return ApplicationStatusHelper::getCandidateCategoryByName($row->role);
            })
            ->editColumn('email', function ($row) {
                return $row->email;
            })
            ->editColumn('phone', function ($row) {
                return $row->phone;
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d-m-Y');
            })
            ->addColumn('action', function ($row) {
                return view('partials.candidate.__action', [
                    'row' => $row
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public static function getJobTable($data)
    {
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('title', function ($row) {
                return $row->job_title;
            })
            ->editColumn('client', function ($row) {
                $client = Client::where('id', $row->client_id)->first();
                return $client->practice_name;
            })
            ->editColumn('date', function($row) {
                return Carbon::createFromFormat('Y-m-d',$row->job_date)->format('d-m-Y');
            })
            ->editColumn('time', function($row) {
                $sDate = Carbon::createFromFormat('H:i:s', $row->job_start_time)->format('H:i'); 
                $eDate = Carbon::createFromFormat('H:i:s', $row->job_end_time)->format('H:i'); 
                return $sDate."-".$eDate;
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d-m-Y');
            })
            ->addColumn('action', function ($row) {
                return view('partials.job.__action', [
                    'row' => $row
                ]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}