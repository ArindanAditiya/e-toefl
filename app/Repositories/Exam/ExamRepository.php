<?php

namespace App\Repositories\Exam;

use App\Models\Exam\Exam;
use App\Repositories\Contracts\Exam\ExamInterface;
use App\Repositories\BaseRepository;

class ExamRepository extends BaseRepository implements ExamInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct(Exam $exam)
    {
        $this->model = $exam->whereNotNull('id');
    }

    public function all($columns = ['*'])
    {
        return $this->model->orderBy('created_at', 'ASC')->get($columns);
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 10)
    {
        $exams = $this->model;
        if(isset($params->search) && !empty($params->search)) $exams->where('title', 'like', '%' . $params->search . '%');
        $exams = $exams->orderBy('created_at', 'ASC')->simplePaginate($limit);
        return $exams;
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $exams = $this->model;
        if(isset($params->search) && !empty($params->search)) $exams->where('title', 'like', '%' . $params->search . '%');
        $exams = $exams->withCount('gradeFinished')->with(['category', 'questionTitle'])->orderBy('created_at', 'ASC')->paginate($limit);
        return $exams;
    }

    public function getAllByCategoryPaginatedWithParams($params, $categoryId, $limit = 10)
    {
        $exams = $this->model;
        if(isset($params->search) && !empty($params->search)) $exams->where('title', 'like', '%' . $params->search . '%');
        $exams = $exams->where('category_id', $categoryId)->with(['category', 'questionTitle', 'transaction'])->orderBy('created_at', 'ASC')->paginate($limit);
        return $exams;
    }

    public function find($id)
    {
        return $this->model->with(['category', 'questionTitle'])->find($id);
    }
}
