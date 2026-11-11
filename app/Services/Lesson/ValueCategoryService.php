<?php

namespace App\Services\Lesson;

use App\Repositories\Contracts\Lesson\ValueCategoryInterface as ValueCategoryRepo;
use App\Services\Contracts\Lesson\ValueCategoryInterface;
use App\Models\Lesson\ValueCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;
use Ramsey\Uuid\Uuid;

class ValueCategoryService implements ValueCategoryInterface
{
    protected $valueCategoryRepo;

    public function __construct(ValueCategoryRepo $valueCategoryRepo)
    {
        $this->valueCategoryRepo = $valueCategoryRepo;
    }

    public function all($columns = ['*'])
    {
        return $this->valueCategoryRepo->all($columns);
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 10)
    {
       return $this->valueCategoryRepo->getAllSimplePaginatedWithParams($params, $limit);
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
       return $this->valueCategoryRepo->getAllPaginatedWithParams($params, $limit);
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
        return $this->valueCategoryRepo->find($id);
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
            return $this->valueCategoryRepo->create($input);
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
            return $this->valueCategoryRepo->update($input, $id);
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
        return $this->valueCategoryRepo->delete($id);
    }

    public function findByField($field, $value, $columns = ['*'])
    {
        return $this->valueCategoryRepo->findByField($field, $value, $columns = ['*']);
    }
}
