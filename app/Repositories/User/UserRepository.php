<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\Contracts\User\UserInterface;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

class UserRepository extends BaseRepository implements UserInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 10)
    {
        $users = $this->model;
        if(isset($params->search) && !empty($params->search)) $users->where('name', 'like', '%' . $params->search . '%');
        $users = $users->where('level', 1)->orderBy('created_at', 'ASC')->simplePaginate($limit);
        return $users;
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $users = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $users->where('name', 'like', '%' . $params->search . '%');
        $users = $users->with(['student'])->orderBy('created_at', 'ASC')->paginate($limit);
        return $users;
    }

    public function getAllUserStudentSimplePaginatedWithParams($params, $limit = 10)
    {
        $users = $this->model->query();
        if(isset($params->search) && !empty($params->search)) $users->where('name', 'like', '%' . $params->search . '%');
        $users = $users->with(['student'])->where('level', 2)->orderBy('created_at', 'ASC')->paginate($limit);
        return $users;
    }

    public function find($id)
    {
        return $this->model->with(['student', 'student.province', 'student.city', 'student.district', 'student.village'])->find($id);
    }

    public function getTotalStudent()
    {
        return $this->model->where('level', 2)->count();
    }

    public function getTotalStudentActive()
    {
        return $this->model->where(['level' => 2, 'is_active' => 1])->count();
    }

    public function getTotalStudentNonActive()
    {
        return $this->model->where(['level' => 2, 'is_active' => 0])->count();
    }

    public function getTotalStudentMember()
    {
        return $this->model->where('level', 2)->whereHas('student', function(Builder $query) {
            $query->where('is_member', 1);
        })->count();
    }
}
