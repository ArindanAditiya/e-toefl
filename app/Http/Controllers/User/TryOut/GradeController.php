<?php

namespace App\Http\Controllers\User\TryOut;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\Exam\GradeInterface as GradeService;
use App\Charts\SectionGradeChart;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Barryvdh\DomPDF\Facade\Pdf;

class GradeController extends Controller
{
    protected $gradeService;

    public function __construct(GradeService $gradeService)
    {
        $this->gradeService = $gradeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('User/TryOut/Grade/Index', [
            'grades' => $this->gradeService->getAllByUserPaginatedWithParams($request)
        ]);
    }

    public function show($id)
    {
        if(!$this->gradeService->find($id)) return abort('404', 'uppss....');

        $grade = $this->gradeService->find($id);
        $rankingExams = $this->gradeService->getRankingByExam($grade->exam_id);

        // $chart = new SectionGradeChart(new LarapexChart, $grade);

        return inertia('User/TryOut/Grade/Show', [
            'grade' => $grade,
            'rankingExams' => $rankingExams,
            'answers' => empty($grade->answers) ? [] : $grade->answers,
        ]);
    }

    public function certificate($id)
    {
        $grade = $this->gradeService->find($id);
        if(!$grade) return abort('404', 'uppss....');
        // return $grade->gradeDetail;
        if($grade->category->name === "TOEFL"){
            $pdf = Pdf::loadView('certificate.certificate_toefl', ['grade' => $grade])->setPaper('a4', 'landscape');
            return $pdf->stream();
        }elseif ($grade->category->name === "TOEIC") {
            $pdf = Pdf::loadView('certificate.certificate_toeic', ['grade' => $grade])->setPaper('a4', 'landscape');
            return $pdf->stream();
        }
        
    }    
}
