<?php

namespace App\Services\MasterData;

use App\Repositories\Contracts\MasterData\FaqInterface as FaqRepo;
use App\Services\Contracts\MasterData\FaqInterface;
use App\Models\MasterData\Faq;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;

class FaqService implements FaqInterface
{
    protected $faqRepo;

    public function __construct(FaqRepo $faqRepo)
    {
        $this->faqRepo = $faqRepo;
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
       return $this->faqRepo->getAllPaginatedWithParams($params, $limit);
    }

     /**
     * Find data by id
     *
     * @param $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->faqRepo->find($id);
    }

    /**
     * @param $request
     *
     * @return mixed
     * @throws \Throwable
     */
    public function create($request)
    {
        $permissions = DB::transaction(function () use ($request) {
            $input = $request->all();
            return $this->faqRepo->create($input);
        });

        return $permissions;
    }

     /**
     * @param $id
     * @param $request
     *
     * @return mixed
     * @throws \Throwable
    */
    public function update($request, $id)
    {
        $permissions = DB::transaction(function () use ($request, $id) {
            $input = $request->except('_token','_method');
            return $this->faqRepo->update($input, $id);
        });

        return $permissions;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function delete($id)
    {
        return $this->faqRepo->delete($id);
    }

    public function getAllWithParams($params)
    {
        return $this->faqRepo->getAllWithParams($params);
    }
}
