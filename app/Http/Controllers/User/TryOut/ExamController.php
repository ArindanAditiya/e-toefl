<?php

namespace App\Http\Controllers\User\TryOut;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\MasterData\CategoryInterface as CategoryService;
use App\Services\Contracts\Exam\ExamInterface as ExamService;
// delete soon
use DB;
use App\Models\Exam\Grade;
use App\Models\Exam\GradeDetail;
use App\Models\Exam\ValueCategoryExam;
use App\Models\Lesson\Question;
use App\Models\Lesson\{DetailValueCategory, ValueCategory};
use Ramsey\Uuid\Uuid as Generator;
use Carbon\Carbon;
use Auth;
use App\Models\Transaction\Transaction;

class ExamController extends Controller
{
    protected $categoryService, $examService;

    public function __construct(
        CategoryService $categoryService,
        ExamService $examService
    )
    {
        $this->categoryService = $categoryService;
        $this->examService = $examService;

        $this->middleware('member', ['only' => ['examStart']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $categoryId)
    {
        if(!$this->categoryService->find($categoryId)) return abort('404', 'uppss....');
        
        return inertia('User/TryOut/Exam/Index', [
            'exams' => $this->examService->getAllByCategoryPaginatedWithParams($request, $categoryId)
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($categoryId, $id)
    {
        if(!$this->categoryService->find($categoryId)) return abort('404', 'uppss....');
        if(!$this->examService->find($id)) return abort('404', 'uppss....');

        //get grade / nilai
        $grade = Grade::where('exam_id', $id)
                    ->where('user_id', auth()->user()->id)
                    ->first();

        return inertia('User/TryOut/Exam/Show', [
            'exam' => $this->examService->find($id),
            'grade' => $grade
        ]);
    }

    public function examReset($id)
    {
        if(!$this->examService->find($id)) return abort('404', 'uppss....');

        $exam = $this->examService->find($id);

        Grade::where(['exam_id' => $exam->id, 'user_id' => Auth::user()->id])->delete();
        
        return redirect()->route('user.categories.lesson-categories.lessons.exams.show', [$exam->category_id, $exam->lesson_category_id, $exam->lesson_id, $exam->id]);
    }

    public function examStart($id, Request $request)
    {
        if(!$this->examService->find($id)) return abort('404', 'uppss....');

        $exam = $this->examService->find($id);

        $transactions = Transaction::where(['user_id' => Auth::user()->id,'voucher_used' => 1, 'exam_id' => $exam->id])->get();

        if(count($transactions) == 0 && $exam->price > 0) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke try out dengan judul <b>'.strtoupper(strtolower($exam->title)).'</b>, silakan lakukan pembelian terlebih dahulu.');
        } 

        $grade = Grade::where('exam_id', $id)->where('user_id', auth()->user()->id)->first();
        $gradeOld = empty($grade->grade_old) ? 0 : $grade->grade_old;

        if($request->repeat == 1) {
            Grade::where('exam_id', $id)->where('user_id', auth()->user()->id)->delete();
            GradeDetail::where('exam_id', $id)->where('user_id', auth()->user()->id)->delete();
        }

        $grade = Grade::where('exam_id', $id)->where('user_id', auth()->user()->id)->first();
        $totalSection = ValueCategory::where('category_id', $exam->category_id)->count();

        if(!$grade) {
            $answerInsert = [];

            $valueExam = ValueCategoryExam::where(['exam_id' => $exam->id, 'section' => 1])->first();

            if($exam->duration_type == 1) {
                $addMinutes = (int) $exam->duration;
            } else if($exam->duration_type == 2) {
                $addMinutes = $valueExam->duration;
            } else {
                $addMinutes = 560;
            }

            //create defaul grade
            $grade = new Grade();
            $grade->category_id = $exam->category_id;
            $grade->exam_id = $exam->id;
            $grade->user_id = auth()->user()->id;
            $grade->duration = $addMinutes;
            $grade->total_tolerance = $exam->total_tolerance;
            $grade->section = 1;
            $grade->total_correct = 0;
            $grade->total_section = $totalSection;
            $grade->grade = 0;
            $grade->grade_old = $gradeOld;
            $grade->start_time = Carbon::now();
            $grade->end_time = Carbon::now()->addMinutes($addMinutes)->addSeconds(2);
            $grade->is_finished = 0;
            $grade->number = 0;

            $question_order = 1;

            for($i = 1; $i <= $totalSection; $i++) {
                if($exam->random_question == 1) {
                    $questions = Question::where('section', $i)->where('question_title_id', $exam->question_title_id)->inRandomOrder()->get();
                } else {
                    $questions = Question::where('section', $i)->where('question_title_id', $exam->question_title_id)->orderBy('created_at', 'ASC')->get();
                }
    
                $navigation_order = 0;
    
                foreach ($questions as $question) {
                    if($question->type == 1) {
                        $navigation_order++;
                    }
                    
                    $options = [];
                    if($question->option_1 != null) $options[] = 1;
                    if($question->option_2 != null) $options[] = 2;
                    if($question->option_3 != null) $options[] = 3;
                    if($question->option_4 != null) $options[] = 4;
                    if($question->option_5 != null) $options[] = 5;
    
                    if($exam->random_answer == 1) {
                        shuffle($options);
                    }
    
                    if(empty($grade->answers)) {
                        $answerInsert[] = [
                            'question_id' => $question->id,
                            'value_category_id' => $question->value_category_id,
                            'type' => $question->type,
                            'direction' => $question->direction,
                            'question_order' => $question_order,
                            'navigation_order' => $navigation_order,
                            'audio_input' => $question->audio_input,
                            'audio' => $question->audio,
                            'audio_played' => (int) $question->audio_played,
                            'audio_played_limit' => (int) $question->audio_played_limit,
                            'audio_answer_time' => $question->audio_answer_time == null || $question->audio_answer_time == 0 ? 0 : (int) $question->audio_answer_time * 1000,
                            'question' => $question->question,
                            'question_answer' => $question->answer,
                            'option_1' => empty($question->option_1) ? null : $question->option_1,
                            'option_2' => empty($question->option_2) ? null : $question->option_2,
                            'option_3' => empty($question->option_3) ? null : $question->option_3,
                            'option_4' => empty($question->option_4) ? null : $question->option_4,
                            'option_5' => empty($question->option_5) ? null : $question->option_5,
                            'section' => $question->section,
                            'answer_order' => implode(",", $options),
                            'answer' => 0,
                            'is_correct' => 'N'
                        ];
                    }
    
                    $question_order++;
                }    
            }
            $grade->answers = $answerInsert;
            $grade->save();
        }

        $quetions = array_column($grade->answers, 'question_id');

        $duration = $grade->end_time > Carbon::now() || empty($grade->end_time) ? $grade->end_time->diffInMilliseconds(Carbon::now()) : 0;

        return redirect()->route('user.exams.exam-show-questions', [$grade->exam_id, $grade->section]);
    }

    public function examShowQuestion($id, $section, Request $request)
    {
        if(!$this->examService->find($id)) return abort('404', 'uppss....');

        $exam = $this->examService->find($id);

        $grade = Grade::where('exam_id', $exam->id)->where('user_id', auth()->user()->id)->first();

        if($grade->is_finished == 1) {
            return redirect()->route('user.categories.exams.show', [$grade->category_id, $grade->exam_id]);
        }

        if($section != $grade->section && empty($request->nextsection)) {
            return redirect()->route('user.exams.exam-show-questions', [$grade->exam_id, 1, $grade->section]);
        }

        $section = $section;

        $valueExam = ValueCategoryExam::where(['exam_id' => $exam->id, 'section' => $section])->first();

        if($exam->duration_type == 1) {
            $addMinutes = (int) $exam->duration;
        } else if($exam->duration_type == 2) {
            $addMinutes = $valueExam->duration;
        } else {
            $addMinutes = 560;
        }

        if($grade->section < $section) {
            $grade->section = $grade->section + 1;
            $grade->number = (int) 0;
            if($exam->duration_type == 2) {
                $grade->end_time = Carbon::now()->addMinutes($addMinutes)->addSeconds(2);
            }
            $grade->update();
            $grade->refresh();
        }

        $duration = $grade->end_time > Carbon::now() || empty($grade->end_time) ? $grade->end_time->diffInMilliseconds(Carbon::now()) : 0;

        $gradeAnswers = $grade['answers'];

        $questionLists = array_filter($gradeAnswers, function ($var) use($section) {
            return ($var['section'] == $section);
        });

        $questionLists = empty($questionLists) ? [] : array_values($questionLists);

        $totalQuetions = count($questionLists);

        return inertia('User/TryOut/Exam/Question', [
            'exam' => $exam,
            'grade' => $grade,
            'questionLists' => $questionLists,
            'duration' => $duration,
            'section' => (int) $grade->section,
            'indexPage' => (int) $grade->number,
            'lastSection' => (int) $grade->total_section,
        ]);
    }

    /**
     * answerQuestion
     *
     * @param  mixed $request
     * @return void
     */
    public function answerQuestion(Request $request)
    {
        $grade = Grade::find($request->grade_id);
        $gradeAnswers = $grade['answers'];
        $index = array_search($request->question_id, array_column($gradeAnswers, 'question_id'));

        $gradeAnswers[$index]['is_correct'] = (int) $gradeAnswers[$index]['question_answer'] == (int) $request->answer ? 'Y' : 'N';
        $gradeAnswers[$index]['answer'] = $request->answer;
        $gradeAnswers = $gradeAnswers;

        $grade->update(['answers' => $gradeAnswers, 'number' => $request->number]);

        return $grade;
    }

    /**
     * answerQuestion
     *
     * @param  mixed $request
     * @return void
     */
    public function audioPlayed(Request $request)
    {
        $grade = Grade::find($request->grade_id);
        $gradeAnswers = $grade['answers'];
        $index = array_search($request->question_id, array_column($gradeAnswers, 'question_id'));
        $gradeAnswers[$index]['audio_played'] = $gradeAnswers[$index]['audio_played'] + 1;
        $gradeAnswers = $gradeAnswers;
        $grade->update(['answers' => $gradeAnswers]);

        return $grade;
    }

    /**
     * decrementTolerance
     *
     * @param  mixed $request
     * @return void
     */
    public function decrementTolerance(Request $request)
    {
        $grade = Grade::find($request->grade_id);
        $total_tolerance = $grade->total_tolerance > 0 ? $grade->total_tolerance - 1 : 0 ;
        $is_blocked = $total_tolerance <= 0 ? 1 : 0;
        $grade->update(['total_tolerance' => $total_tolerance, 'is_blocked' => $is_blocked]);
    }

    /**
     * endExam
     *
     * @param  mixed $request
     * @return void
     */
    public function endExam($examId, Request $request)
    {
        if(!$this->examService->find($examId)) return abort('404', 'uppss....');

        $exam = $this->examService->find($examId);

        //update nilai di table grades
        $grade = Grade::find($request->grade_id);
        $gradeAnswers = $grade['answers'];

        $totalCorrectPerSection = [];
        for($i = 1; $i <= 3; $i++) {
            $totalCorrect = array_filter($gradeAnswers, function ($var) use($i) {
                return ($var['is_correct'] == "Y" && $var['section'] == $i && $var['type'] == 1);
            });

            $totalCorrectPerSection[] = count($totalCorrect);
        }

        $totalCorrect = array_filter($gradeAnswers, function ($var) {
            return ($var['is_correct'] == "Y" && $var['answer'] != 0 && $var['type'] == 1);
        });

        $totalAnswer = array_filter($gradeAnswers, function ($var) {
            return ($var['answer'] != 0 && $var['type'] == 1);
        });

        $totalWrong = array_filter($gradeAnswers, function ($var) {
            return ($var['is_correct'] == "N" && $var['type'] == 1);
        });

        $totalQuestionGrade = array_filter($gradeAnswers, function ($var) {
            return ($var['type'] == 1);
        });

        $count_correct_answer = count($totalCorrect);
        $grade_exam = round($count_correct_answer / count($totalQuestionGrade) * 100, 2);

        $grade_exam = 0;
        $count_correct_answer = count($totalCorrect);
        
        if($exam->questionTitle->assessment_type == 3) {
            foreach($totalCorrect as $correct) {
                $grade_exam += $correct['correct_point'];
            }
        } elseif($exam->questionTitle->assessment_type == 4) {
            foreach($totalAnswer as $correct) {
                $grade_exam += $correct['point_'.$correct['answer']];
            }
        } else {
            $grade_exam = round($count_correct_answer / count($totalQuestionGrade) * 100, 2);
        }

        $grade->end_time = Carbon::now();
        $grade->total_correct = $count_correct_answer;
        $grade->is_finished = 1;
        $grade->update();

        $valueCategories = ValueCategory::where('category_id', $exam->category_id)->orderBy('section', 'ASC')->get();

        if($exam->questionTitle->add_value_category == 1 && ($exam->questionTitle->assessment_type == 1)) {

            $grades = [];

            foreach($valueCategories as $index => $valueCategory) {
                if($exam->questionTitle->assessment_type == 1) {
                    $totalCorrect = array_filter($gradeAnswers, function ($var) use($valueCategory) {
                        return ($var['value_category_id'] == $valueCategory->id && $var['is_correct'] == "Y" && $var['type'] == 1);
                    });

                    $totalWrong = array_filter($gradeAnswers, function ($var) use($valueCategory) {
                        return ($var['value_category_id'] == $valueCategory->id && $var['is_correct'] == "N" && $var['type'] == 1);
                    });
                }

                $totalCorrectFinal = count($totalCorrect);
                $totalWrongFinal = count($totalWrong);
                $totalQuestion = (int) $totalCorrectFinal + (int) $totalWrongFinal;

                $grades[] = [
                    'category_id' => $exam->category_id,
                    'lesson_category_id' => $exam->lesson_category_id,
                    'lesson_id' => $exam->lesson_id,
                    'exam_id' => $exam->id,
                    'total_correct' => $totalCorrectFinal,
                    'total_wrong' => $totalWrongFinal,
                    'value_category_id' => $valueCategory->id,
                    'value_category_name' => $valueCategory->name,
                    'value_category_assessment_type' => $valueCategory->assessment_type,
                ];

                if($valueCategory->assessment_type == 1) {
                    $grades[$index]['grade'] =($totalCorrectFinal / $totalQuestion) * 100;
                }

                if($valueCategory->assessment_type == 2) {
                    $grades[$index]['grade'] = $totalCorrectFinal;
                }
            }

            $result = [];

            $finalGradeExam = 0;

            foreach($grades as $gradeData) {
                $detailValueCategories = DetailValueCategory::where('value_category_id', $gradeData['value_category_id'])->get();
                $finalGrade = 0;
                foreach($detailValueCategories as $detailValueCategory) {
                    if($gradeData['grade'] >= $detailValueCategory->min_grade && $gradeData['grade'] <= $detailValueCategory->max_grade) {
                        $finalGrade = $detailValueCategory->final_grade;
                    }
                }

                $result[] = [
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'category_id' => $gradeData['category_id'],
                    'exam_id' => $gradeData['exam_id'],
                    'user_id' => auth()->user()->id,
                    'value_category_id' => $gradeData['value_category_id'],
                    'grade_id' => $grade->id,
                    'total_correct' => $grade->is_blocked == 1 ? 0 : $gradeData['total_correct'],
                    'total_wrong' => $grade->is_blocked == 1 ? 0 : $gradeData['total_wrong'],
                    'grade' => $grade->is_blocked == 1 ? 0 : $finalGrade,
                ];

                $finalGradeExam += $grade->is_blocked == 1 ? 0 : $finalGrade;
            }

            if($grade->category_id == "f968e7a3-74ed-4090-b9bc-99e7c36f2a69") {
                $finalGradeExam = round(($finalGradeExam / 3) * 10);
            }

            GradeDetail::insert($result);

            $grade->grade = $finalGradeExam;
            $grade->grade_old = empty($grade->grade_old) ? $finalGradeExam : $grade->grade_old;
            $grade->update();
        }

        //redirect hasil
        return redirect()->route('user.categories.exams.show', [$exam->category_id, $exam->id]);
    }
}
