<?php

namespace App\Http\Controllers\User\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\Transaction\TransactionInterface as TransactionService;
use App\Services\Contracts\Transaction\BankInterface as BankService;
use App\Services\Contracts\Exam\ExamInterface as ExamService;

class TransactionController extends Controller
{
    protected $transactionService, $bankService, $examService;

    public function __construct(TransactionService $transactionService, BankService $bankService, ExamService $examService)
    {
        $this->transactionService = $transactionService;
        $this->bankService = $bankService;
        $this->examService = $examService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('User/Transaction/Transaction/Index', [
            'transactions' => $this->transactionService->getAllPaginatedWithParamsByUser($request)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$this->transactionService->find($id)) return abort('404', 'uppss....');

        return inertia('User/Transaction/Transaction/Show', [
            'transaction' => $this->transactionService->find($id),
            'banks' => $this->bankService->all()
        ]);
    }

    public function buy(Request $request, $id)
    {
        try {
            $exam = $this->examService->find($id);

            if(!$exam) return abort('404', 'uppss....');

            if($this->transactionService->checkTransactionPendingByUser()) {
                return redirect()->route('user.categories.exams.index', $exam->category_id)->with('error', 'Anda memiliki transaksi yang terpending, silakan selesaikan terlebih dahulu transaksi tersebut atau silakan hubungi admin');
            }

            $transaction = $this->transactionService->createTransacationWithExamId($id);

            return redirect()->route('user.transactions.show', $transaction->id);

        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan. '.$e->getMessage().' - file: '. $e->getFile(). ' -line '. $e->getLine());
        }
    }
}
