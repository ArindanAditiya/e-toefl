<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Exam\Grade;
use App\Models\Transaction\Transaction;
use Auth;
use Carbon\Carbon;
use App\Services\Contracts\Transaction\TransactionInterface as TransactionService;
use App\Services\Contracts\Exam\GradeInterface as GradeService;
use App\Services\Contracts\MasterData\AnnouncementInterface as AnnouncementService;

class DashboardController extends Controller
{
    protected $transactionService, $gradeService, $announcementService;

    public function __construct(TransactionService $transactionService, GradeService $gradeService, AnnouncementService $announcementService)
    {
        $this->transactionService = $transactionService;
        $this->gradeService = $gradeService;
        $this->announcementService = $announcementService;

    }
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $totalExamToday = Grade::where(['user_id' => Auth::user()->id, 'is_finished' => 1])->whereDate('created_at', Carbon::now())->count();
        $totalTransactionThisMonth = $data = Transaction::whereMonth('created_at', Carbon::now()->month)->whereYear('created_at', Carbon::now()->year)->count();
        
        return inertia('User/Dashboard/Index', [
            'totalExamToday' => $totalExamToday,
            'transactionSummaries' =>  $this->transactionService->getSummaryTransactionByUser(),
            'totalTransactionThisMonth' =>  $totalTransactionThisMonth,
            'gradeSummaries' =>  $this->gradeService->getSummaryExamByUser(),
            'announcementSummaries' =>  $this->announcementService->getAnnouncementSummaries(),
        ]);
    }
}
