<?php

namespace App\Repositories\Transaction;

use App\Models\Transaction\Bank;
use App\Repositories\Contracts\Transaction\BankInterface;
use App\Repositories\BaseRepository;

class BankRepository extends BaseRepository implements BankInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct(Bank $bank)
    {
        $this->model = $bank->whereNotNull('id');
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 10)
    {
        $banks = $this->model;
        if(isset($params->search) && !empty($params->search)) $banks->where('bank_name', 'like', '%' . $params->search . '%');
        $banks = $banks->orderBy('created_at', 'ASC')->simplePaginate($limit);
        return $banks;
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $banks = $this->model;
        if(isset($params->search) && !empty($params->search)) $banks->where('bank_name', 'like', '%' . $params->search . '%');
        $banks = $banks->orderBy('created_at', 'ASC')->paginate($limit);
        return $banks;
    }

    public function all($columns = ['*'])
    {
        return $this->model->orderBy('created_at', 'ASC')->get($columns);
    }
}
