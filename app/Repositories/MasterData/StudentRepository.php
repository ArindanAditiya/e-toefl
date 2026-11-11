<?php

namespace App\Repositories\MasterData;

use App\Models\MasterData\Student;
use App\Repositories\Contracts\MasterData\StudentInterface;
use App\Repositories\BaseRepository;

class StudentRepository extends BaseRepository implements StudentInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct(Student $student)
    {
        $this->model = $student->whereNotNull('id');
    }

    public function find($id)
    {
        return $this->model->with(['user','province', 'city', 'district', 'village'])->find($id);
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 10)
    {
        $students = $this->model;
        if(isset($params->search) && !empty($params->search)) $students->where('name', 'like', '%' . $params->search . '%');
        $students = $students->orderBy('created_at', 'ASC')->simplePaginate($limit);
        return $students;
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $students = $this->model;
        if(isset($params->search) && !empty($params->search)) $students->where('name', 'like', '%' . $params->search . '%');
        $students = $students->with(['user','province', 'city', 'district', 'village'])->orderBy('created_at', 'ASC')->paginate($limit);
        return $students;
    }
}
