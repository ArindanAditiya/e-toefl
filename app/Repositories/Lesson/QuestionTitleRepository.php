<?php

namespace App\Repositories\Lesson;

use App\Models\Lesson\QuestionTitle;
use App\Repositories\Contracts\Lesson\QuestionTitleInterface;
use App\Repositories\BaseRepository;

class QuestionTitleRepository extends BaseRepository implements QuestionTitleInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct(QuestionTitle $questionTitle)
    {
        $this->model = $questionTitle->whereNotNull('id');
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 10)
    {
        $questionTitles = $this->model;
        if(isset($params->search) && !empty($params->search)) $questionTitles->where('name', 'like', '%' . $params->search . '%');
        $questionTitles = $questionTitles->orderBy('created_at', 'ASC')->simplePaginate($limit);
        return $questionTitles;
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $questionTitles = $this->model;
        if(isset($params->search) && !empty($params->search)) $questionTitles->where('name', 'like', '%' . $params->search . '%');
        $questionTitles = $questionTitles->with(['category'])->orderBy('created_at', 'ASC')->paginate($limit);
        return $questionTitles;
    }

    public function find($id)
    {
        return $this->model->with(['category'])->find($id);
    }

    public function getAllByCategory($categoryId)
    {
        return $this->model->where('category_id', $categoryId)->orderBy('created_at', 'ASC')->get();
    }
}
