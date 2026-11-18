<?php

namespace App\Http\Controllers\User\TryOut;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\Exam\GradeInterface as GradeService;
use App\Charts\SectionGradeChart;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

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

        $category = $grade->category;
        if (!$category || $category->trashed()) {
            abort(404, 'Sertifikat tidak tersedia karena kategori ujian sudah dihapus.');
        }

        if ($category->certificate_template) {
            $storage = Storage::disk('public');
            $certificatePathPrefix = 'upload_files/certificates/categories/';
            $certificateFilename = substr(
                $category->certificate_template,
                strlen($certificatePathPrefix)
            );
            if (
                !\Illuminate\Support\Str::startsWith(
                    $category->certificate_template,
                    $certificatePathPrefix
                ) ||
                !$certificateFilename ||
                basename($certificateFilename) !== $certificateFilename ||
                !$storage->exists($category->certificate_template)
            ) {
                abort(404, 'File template sertifikat kategori tidak ditemukan.');
            }
            $background = $storage->path($category->certificate_template);
        } else {
            $defaultTemplates = [
                'TOEFL' => public_path('assets/certificate/toefl.jpg'),
                'TOEIC' => public_path('assets/certificate/toeic.jpg'),
            ];
            $background = $defaultTemplates[$category->name] ?? null;

            if (!$background || !is_file($background)) {
                abort(404, 'Template sertifikat belum diunggah untuk kategori ini.');
            }
        }

        $pdf = Pdf::loadView('certificate.category', [
            'grade' => $grade,
            'background' => $background,
        ])->setPaper('a4', 'landscape');

        return $pdf->stream();
    }
}
