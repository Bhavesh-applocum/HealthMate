<?php

namespace App\Http\Controllers\Admin;

use App\Application;
use App\Candidate;
use App\Client;
use App\Helper\Constants;
use App\Helper\DataTableHelper;
use App\Helpers\ApplicationStatusHelper;
use App\Helpers\GeneralHelper;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\Job;
use App\Timesheet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        if (!Auth::guard('admin')->check()) {
            Session::put('redirect_to', URL::full());
            return redirect()->route('login');
        }
    }

    public function index()
    {
        return view('admin.index');
    }

    public function dashboard()
    {
        $clientCount = Client::whereDate('created_at', Carbon::today())->count();
        $candidateCount = Candidate::whereDate('created_at', Carbon::today())->count();
        $applicationCount = Application::whereDate('created_at', Carbon::today())->count();
        $invoiceCount = Invoice::whereDate('created_at', Carbon::today())->count();
        $timesheetCount = Timesheet::whereDate('created_at', Carbon::today())->count();
        $CountDurationForDashboard = Constants::CountDurationForDashboard;
        return view('admin.dashboard.index', [
            'clientCount' => $clientCount,
            'candidateCount' => $candidateCount,
            'applicationCount' => $applicationCount,
            'invoiceCount' => $invoiceCount,
            'timesheetCount' => $timesheetCount,
            'CountDurationForDashboard' => $CountDurationForDashboard,
        ]);
    }

    public function getDashboardInfo()
    {
        $time = $_GET['time'];
        $type = $_GET['type'];

        $count = GeneralHelper::getDashboardCounts($type, $time);
        // dd($count);
        // find the text from array of Constants::CountDurationForDashboard where 'time' = time
        $text = array_filter(Constants::CountDurationForDashboard, function ($e) use ($time) {
            return $e['time'] == $time;
        });
        $data = [
            'value' => $count,
            'duration' => $text[$time]['text'],
        ];

        return response()->json($data);
    }

    public function getTimesheetChartInfo()
    {
        // $timesheetCount =  Timesheet::select('status', DB::raw('COUNT(*) as total'))->groupBy('status')->get();
        $timesheetCount1 = Timesheet::select('status')->selectRaw('COUNT(*) as total')->groupBy('status')->get();
        $data = [];
        $totalStatus = config('constant.Timesheet_status_client');
        foreach ($totalStatus as $key => $value) {
            // $key = 0,1,2,3
            // $value = 'Assigned','Pending','Approved','Dispute'
            $count = 0;
            foreach ($timesheetCount1 as $val) {
                if ($val->status == $key) {
                    $count = $val->total;
                }
            }

            $data[] = [
                'name' => $value,
                'y' => $count,
                'status' => $key
            ];
        }
        // foreach ($timesheetCount1 as $value) {
        //     $data[] = [
        //         'name' => ApplicationStatusHelper::getTimesheetStatusByStatus($value->status),
        //         'y' => $value->total,
        //         'status' => $value->status
        //     ];
        // }
        // dd($data);
        return response()->json($data);
    }

    //function of category wise candidate and created job
    public function getCategoryWiseCandidateAndCreatedJob()
    {
        $totalCategory = config('constant.job_Category');
        $data = [];
        $canArr = [];
        $jobArr = [];
        // [{
        //     name: 'Number of Candidates',
        //     data: [92.5, 73.1, 64.8, 49.0],
        //     tooltip: {
        //       valueSuffix: ' kg'
        //     }
        //   }, {
        //     name: 'Creted Jobs',
        //     data: [33.7, 27.1, 24.9, 21.2],
        //     yAxis: 1
        //   }]

        $candidateCount = Candidate::select('role')->selectRaw('COUNT(*) as total')->groupBy('role')->get();
        $jobCount = Job::select('job_category')->selectRaw('COUNT(*) as total')->groupBy('job_category')->get();
        foreach ($totalCategory as $key => $value) {
            $canTotal = 0;
            $jobTotal = 0;
            foreach ($candidateCount as $can) {
                if ($can->role == $key) {
                    $canTotal = $can->total;
                }
            }
            foreach ($jobCount as $job) {
                if ($job->job_category == $key) {
                    $jobTotal = $job->total;
                }
            }
            array_push($canArr, $canTotal);
            array_push($jobArr, $jobTotal);
        }

        $data = [[
            'name' => 'Number of Candidates',
            'data' => $canArr,
        ], [
            'name' => 'Created Jobs',
            'data' => $jobArr,
        ]];


        return response()->json([
            'data' => $data,
            'category' => $totalCategory
        ]);
    }

    public function Clientindex(Request $request){
        
    }
}
