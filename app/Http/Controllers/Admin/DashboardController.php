<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Services\Contracts\Transaction\TransactionInterface as TransactionService;
use App\Services\Contracts\User\UserInterface as UserService;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use App\Charts\VisitorCounterChart;

class DashboardController extends Controller
{
    protected $transactionService, $userService;

    public function __construct(TransactionService $transactionService, UserService $userService)
    {
        $this->transactionService = $transactionService;
        $this->userService = $userService;
    }

    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $chart = new VisitorCounterChart(new LarapexChart);

        return inertia('Admin/Dashboard/Index', [
            'totalStudent' => number_format($this->userService->getTotalStudent()),
            'totalStudentActive' => number_format($this->userService->getTotalStudentActive()),
            'totalStudentNonActive' => number_format($this->userService->getTotalStudentNonActive()),
            'totalStudentMember' => number_format($this->userService->getTotalStudentMember()),

            'totalTransactionToday' => number_format($this->transactionService->getTotalTransactionToday()),
            'totalTransactionMonthly' => number_format($this->transactionService->getTotalTransactionMonthly()),
            'totalTransactionYearly' => number_format($this->transactionService->getTotalTransactionYearly()),

            'totalTransactionPending' => number_format($this->transactionService->getTotalTransactionPending()),
            'totalTransactionPaid' => number_format($this->transactionService->getTotalTransactionPaid()),
            'totalTransactionDone' => number_format($this->transactionService->getTotalTransactionDone()),
            'totalTransactionFailed' => number_format($this->transactionService->getTotalTransactionFailed()),

            'transactions' => $this->transactionService->getTransactionMonthly(),
            // 'chart' => $chart->build()
        ]);
    }

    public function upload(Request $request)
    {
        $fileName = Carbon::now()->format('Ymdhis').$request->file('file')->getClientOriginalName();
        $path = $request->file('file')->storeAs('/upload_files/questions', $fileName, 'public');
        return response()->json(['location' => "/storage/$path"]);
    }

    public function uploadAnnouncement(Request $request)
    {
        $fileName = Carbon::now()->format('Ymdhis').$request->file('file')->getClientOriginalName();
        $path = $request->file('file')->storeAs('/upload_files/announcements', $fileName, 'public');
        return response()->json(['location' => "/storage/$path"]);
    }
}
