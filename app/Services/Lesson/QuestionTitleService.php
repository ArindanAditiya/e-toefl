<?php

namespace App\Services\Lesson;

use App\Repositories\Contracts\Lesson\QuestionTitleInterface as QuestionTitleRepo;
use App\Services\Contracts\Lesson\QuestionTitleInterface;
use App\Models\Lesson\QuestionTitle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;
use Ramsey\Uuid\Uuid;

class QuestionTitleService implements QuestionTitleInterface
{
    protected $questionTitleRepo;

    public function __construct(QuestionTitleRepo $questionTitleRepo)
    {
        $this->questionTitleRepo = $questionTitleRepo;
    }

    public function all($columns = ['*'])
    {
        return $this->questionTitleRepo->all($columns);
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 10)
    {
       return $this->questionTitleRepo->getAllSimplePaginatedWithParams($params, $limit);
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
       return $this->questionTitleRepo->getAllPaginatedWithParams($params, $limit);
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
        return $this->questionTitleRepo->find($id);
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
            $input['assessment_type'] = $request->add_value_category == 0 ? 0 : $request->assessment_type;
            return $this->questionTitleRepo->create($input);
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
            $input['assessment_type'] = $request->add_value_category == 0 ? 0 : $request->assessment_type;
            $input['minimum_grade'] = $request->show_answer == 0 ? 0 : $request->minimum_grade;
            return $this->questionTitleRepo->update($input, $id);
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
        return $this->questionTitleRepo->delete($id);
    }

    public function getAllByCategory($categoryId)
    {
        return $this->questionTitleRepo->getAllByCategory($categoryId);
    }
}
