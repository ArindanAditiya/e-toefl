<?php

namespace App\Services\MasterData;

use App\Repositories\Contracts\MasterData\StudentInterface as StudentRepo;
use App\Services\Contracts\MasterData\StudentInterface;
use App\Models\MasterData\Student;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;
use Ramsey\Uuid\Uuid;

class StudentService implements StudentInterface
{
    protected $studentRepo;

    public function __construct(StudentRepo $studentRepo)
    {
        $this->studentRepo = $studentRepo;
    }

    public function all()
    {
        return $this->studentRepo->all();
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 10)
    {
       return $this->studentRepo->getAllSimplePaginatedWithParams($params, $limit);
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
       return $this->studentRepo->getAllPaginatedWithParams($params, $limit);
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
        return $this->studentRepo->find($id);
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
            return $this->studentRepo->create($input);
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
            return $this->studentRepo->update($input, $id);
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
        return $this->studentRepo->delete($id);
    }
}
