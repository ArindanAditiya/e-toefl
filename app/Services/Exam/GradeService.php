<?php

namespace App\Services\Exam;

use App\Repositories\Contracts\Exam\GradeInterface as GradeRepo;
use App\Services\Contracts\Exam\GradeInterface;
use App\Models\Exam\Exam;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;

class GradeService implements GradeInterface
{
    protected $gradeRepo;

    public function __construct(GradeRepo $gradeRepo)
    {
        $this->gradeRepo = $gradeRepo;
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
        return $this->gradeRepo->find($id);
    }

    public function getAllByUserPaginatedWithParams($params, $limit = 8)
    {
        return $this->gradeRepo->getAllByUserPaginatedWithParams($params, $limit);
    }

    public function getSummaryExamByUser($limit = 5)
    {
        return $this->gradeRepo->getSummaryExamByUser($limit);
    }

    public function getRankingByExam($examId)
    {
        return $this->gradeRepo->getRankingByExam($examId);
    }
}
