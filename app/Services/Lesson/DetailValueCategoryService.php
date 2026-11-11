<?php

namespace App\Services\Lesson;

use App\Repositories\Contracts\Lesson\DetailValueCategoryInterface as DetailValueCategoryRepo;
use App\Services\Contracts\Lesson\DetailValueCategoryInterface;
use App\Models\Lesson\DetailValueCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;
use Ramsey\Uuid\Uuid;

class DetailValueCategoryService implements DetailValueCategoryInterface
{
    protected $detailValueCategoryRepo;

    public function __construct(DetailValueCategoryRepo $detailValueCategoryRepo)
    {
        $this->detailValueCategoryRepo = $detailValueCategoryRepo;
    }

    public function all($columns = ['*'])
    {
        return $this->detailValueCategoryRepo->all($columns);
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 10)
    {
       return $this->detailValueCategoryRepo->getAllSimplePaginatedWithParams($params, $limit);
    }

    public function getAllByValueCategoryPaginatedWithParams($valueCategoryId, $params, $limit = 10)
    {
       return $this->detailValueCategoryRepo->getAllByValueCategoryPaginatedWithParams($valueCategoryId, $params, $limit);
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
        return $this->detailValueCategoryRepo->find($id);
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
            return $this->detailValueCategoryRepo->create($input);
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
            return $this->detailValueCategoryRepo->update($input, $id);
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
        return $this->detailValueCategoryRepo->delete($id);
    }

    public function findByField($field, $value, $columns = ['*'])
    {
        return $this->detailValueCategoryRepo->findByField($field, $value, $columns = ['*']);
    }
}
