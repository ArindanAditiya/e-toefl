<?php

namespace App\Services\User;

use App\Repositories\Contracts\User\UserInterface as UserRepo;
use App\Services\Contracts\User\UserInterface;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;
use Ramsey\Uuid\Uuid;

class UserService implements UserInterface
{
    protected $userRepo;

    public function __construct(UserRepo $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function all()
    {
        return $this->userRepo->all();
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 10)
    {
       return $this->userRepo->getAllSimplePaginatedWithParams($params, $limit);
    }

    public function getAllUserStudentSimplePaginatedWithParams($params, $limit = 10)
    {
       return $this->userRepo->getAllUserStudentSimplePaginatedWithParams($params, $limit);
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
       return $this->userRepo->getAllPaginatedWithParams($params, $limit);
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
        return $this->userRepo->find($id);
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
            $input['password'] = bcrypt($request->password);
            return $this->userRepo->create($input);
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
            return $this->userRepo->update($request, $id);
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
        return $this->userRepo->delete($id);
    }

    public function getTotalStudent()
    {
        return $this->userRepo->getTotalStudent();
    }

    public function getTotalStudentActive()
    {
        return $this->userRepo->getTotalStudentActive();
    }

    public function getTotalStudentNonActive()
    {
        return $this->userRepo->getTotalStudentNonActive();
    }

    public function getTotalStudentMember()
    {
        return $this->userRepo->getTotalStudentMember();
    }
}
