<?php

namespace App\Repositories\Lesson;

use App\Models\Lesson\Question;
use App\Repositories\Contracts\Lesson\QuestionInterface;
use App\Repositories\BaseRepository;

class QuestionRepository extends BaseRepository implements QuestionInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct(Question $question)
    {
        $this->model = $question->whereNotNull('id');
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $questions = $this->model;
        if(isset($params->search) && !empty($params->search)) $questions->where('question', 'like', '%' . $params->search . '%');
        $questions = $questions->with(['questionTitle','valueCategory'])->orderBy('created_at', 'ASC')->paginate($limit);
        return $questions;
    }

    public function getByQuestionTitlePaginatedWithParams($params, $questionTitleId, $limit = 10)
    {
        $questions = $this->model;
        if(isset($params->search) && !empty($params->search)) $questions->where('question', 'like', '%' . $params->search . '%');
        $questions = $questions->where('question_title_id', $questionTitleId)->with(['questionTitle','valueCategory'])->orderBy('created_at', 'ASC')->paginate($limit);
        return $questions;
    }
}
