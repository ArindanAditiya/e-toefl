<?php

namespace App\Services\Exam;

use App\Repositories\Contracts\Exam\ExamInterface as ExamRepo;
use App\Services\Contracts\Exam\ExamInterface;
use App\Models\Exam\Exam;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Lesson\ValueCategory;
use App\Models\Exam\ValueCategoryExam;
use Auth;

class ExamService implements ExamInterface
{
    protected $examRepo;

    public function __construct(ExamRepo $examRepo)
    {
        $this->examRepo = $examRepo;
    }

    public function all()
    {
        return $this->examRepo->all();
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 10)
    {
       return $this->examRepo->getAllSimplePaginatedWithParams($params, $limit);
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
       return $this->examRepo->getAllPaginatedWithParams($params, $limit);
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
        return $this->examRepo->find($id);
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
            $input['duration'] = $request->duration_type == 2 ? null : $request->duration;
            $exam =  $this->examRepo->create($input);

            if($request->duration_type == 2) {
                $valueCategories = ValueCategory::where('category_id', $request->category_id)->orderBy('section', 'ASC')->get();
                foreach($valueCategories as $index => $valueCategory) {
                    ValueCategoryExam::create([
                        'exam_id' => $exam->id,
                        'value_category_id' => $valueCategory->id,
                        'section' => $index + 1,
                        'index' => $index,
                        'duration' => $request->duration_section[$index],
                    ]);
                }
            }
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
            $input['duration'] = $request->duration_type == 2 ? null : $request->duration;

            ValueCategoryExam::where('exam_id', $id)->delete();

            $this->examRepo->update($input, $id);

            if($request->duration_type == 2) {
                $valueCategories = ValueCategory::where('category_id', $request->category_id)->orderBy('section', 'ASC')->get();
                foreach($valueCategories as $index => $valueCategory) {
                    ValueCategoryExam::create([
                        'exam_id' => $id,
                        'value_category_id' => $valueCategory->id,
                        'section' => $index + 1,
                        'index' => $index,
                        'duration' => $request->duration_section[$index],
                    ]);
                }
            }

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
        return $this->examRepo->delete($id);
    }

    public function getAllByCategoryPaginatedWithParams($params, $categoryId, $limit = 8)
    {
        return $this->examRepo->getAllByCategoryPaginatedWithParams($params,$categoryId, $limit);
    }
}
