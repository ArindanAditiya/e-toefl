<?php

namespace App\Http\Controllers\Admin\Exam;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\Exam\ExamInterface as ExamService;
use App\Services\Contracts\MasterData\CategoryInterface as CategoryService;
use App\Services\Contracts\Exam\GradeInterface as GradeService;
use App\Http\Requests\Exam\ExamRequest;
use App\Models\Exam\ValueCategoryExam;

class ExamController extends Controller
{
    protected $examService, $categoryService;

    public function __construct(GradeService $gradeService, ExamService $examService, CategoryService $categoryService)
    {
        $this->examService = $examService;
        $this->categoryService = $categoryService;
        $this->gradeService = $gradeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/Exam/Exam/Index', [
            'exams' => $this->examService->getAllPaginatedWithParams($request)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Admin/Exam/Exam/Create')->with([
            'categories' => $this->categoryService->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExamRequest $request)
    {
        try {
            #store
            $this->examService->create($request);

            #Bump....
            return redirect()->route('admin.exams.index');

        } catch(\Exception $e) {
            #get message
            session()->flash('failed', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            #redirect
            return redirect()->back()->withInput($request->all());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$this->examService->find($id)) return abort('404', 'uppss....');

        $exam = $this->examService->find($id);
        $rangkingExams = $this->gradeService->getRankingByExam($exam->id);

        return inertia('Admin/Exam/Exam/Show', [
            'exam' => $this->examService->find($id),
            'rankingExams' => $rangkingExams,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!$this->examService->find($id)) return abort('404', 'uppss....');

        $exam = $this->examService->find($id);

        $duration_section = ValueCategoryExam::where('exam_id', $exam->id)->orderBy('index', 'ASC')->pluck('duration');

        return inertia('Admin/Exam/Exam/Edit', [
            'exam' => $exam,
            'categories' => $this->categoryService->all(),
            'duration_section' => $duration_section,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExamRequest $request, $id)
    {
        try {
            if(!$this->examService->find($id)) return abort('404', 'uppss....');

            #update
            $this->examService->update($request, $id);

            #Bump....
            return redirect()->route('admin.exams.index');

        } catch(\Exception $e) {
            #get message
            session()->flash('failed', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            #redirect
            return redirect()->back()->withInput($request->all());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            #delete
            $this->examService->delete($id);

            #Bump....
            return redirect()->route('admin.exams.index');

        } catch(\Exception $e) {
            #get message
            session()->flash('failed', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            #redirect
            return redirect()->back()->withInput($request->all());
        }
    }

    public function unblocked($id)
    {
        $grade = $this->gradeService->find($id);
        $grade->delete();

        return redirect()->back();
    }
}
