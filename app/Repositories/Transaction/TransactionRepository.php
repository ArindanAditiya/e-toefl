<?php

namespace App\Repositories\Transaction;

use App\Models\Transaction\Transaction;
use App\Repositories\Contracts\Transaction\TransactionInterface;
use App\Repositories\BaseRepository;
use Auth;
use Carbon\Carbon;

class TransactionRepository extends BaseRepository implements TransactionInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct(Transaction $transaction)
    {
        $this->model = $transaction;
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 10)
    {
        $transactions = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $transactions->where('code', 'like', '%' . $params->search . '%');
        $transactions = $transactions->orderBy('created_at', 'DESC')->simplePaginate($limit);
        return $transactions;
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $transactions = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $transactions->where('code', 'like', '%' . $params->search . '%');
        $transactions = $transactions->with(['user', 'exam', 'exam.category'])->orderBy('created_at', 'ASC')->paginate($limit);
        return $transactions;
    }

    public function getTransactionMonthly($limit = 10)
    {
        $transactions = $this->model->whereMonth('created_at', Carbon::now()->format('m'))->whereYear('created_at', Carbon::now()->format('Y'))->with(['user', 'exam', 'exam.category'])->orderBy('created_at', 'DESC')->paginate($limit);
        return $transactions;
    }

    public function find($id)
    {
        return $this->model->with(['exam','user', 'exam.category', 'user.student', 'user.student.province', 'user.student.city', 'user.student.district', 'user.student.village'])->find($id);
    }

    public function getAllPaginatedWithParamsByUser($params, $limit = 10)
    {
        $transactions = $this->model;
        if(isset($params->search) && !empty($params->search)) $transactions->where('code', 'like', '%' . $params->search . '%');
        $transactions = $transactions->with(['user', 'exam.category'])->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate($limit);
        return $transactions;
    }

    public function getAllVoucherActivatedPaginatedWithParamsByUser($params, $limit = 10)
    {
        $transactions = $this->model;
        if(isset($params->search) && !empty($params->search)) $transactions->where('code', 'like', '%' . $params->search . '%');
        $transactions = $transactions->with(['exam', 'exam.category'])->where('voucher_activated', 1)->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->paginate($limit);
        return $transactions;
    }

    public function getSummaryTransactionByUser($limit = 5)
    {
        $transactions = $this->model;
        $transactions = $transactions->with(['exam', 'exam.category'])->where('user_id', Auth::user()->id)->orderBy('created_at', 'DESC')->limit($limit)->get();
        return $transactions;
    }

    public function getTotalTransactionToday()
    {
        return $this->model->whereDate('created_at', Carbon::now())->count();
    }

    public function getTotalTransactionMonthly()
    {
        return $this->model->whereMonth('created_at', Carbon::now()->format('m'))->whereYear('created_at', Carbon::now()->format('Y'))->count();
    }

    public function getTotalTransactionYearly()
    {
        return $this->model->whereYear('created_at', Carbon::now()->format('Y'))->count();
    }

    public function getTotalTransactionPending()
    {
        return $this->model->where('transaction_status', 'pending')->count();
    }

    public function getTotalTransactionPaid()
    {
        return $this->model->where('transaction_status', 'paid')->count();
    }

    public function getTotalTransactionDone()
    {
        return $this->model->where('transaction_status', 'done')->count();
    }

    public function getTotalTransactionFailed()
    {
        return $this->model->where('transaction_status', 'failed')->count();
    }

    public function checkTransactionPendingByUser()
    {
        return $this->model->where(['user_id' => Auth::user()->id, 'transaction_status' => 'pending'])->first();
    }
}
