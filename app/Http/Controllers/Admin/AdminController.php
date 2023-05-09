<?php

namespace App\Http\Controllers\Admin;

use App\Application;
use App\Candidate;
use App\Client;
use App\Helper\Constants;
use App\Helpers\GeneralHelper;
use App\Http\Controllers\Controller;
use App\Invoice;
use App\Timesheet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
}