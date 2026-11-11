<?php

namespace App\Http\Controllers\Admin\Lesson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\Lesson\QuestionInterface as QuestionService;
use App\Services\Contracts\Lesson\QuestionTitleInterface as QuestionTitleService;
use App\Services\Contracts\Lesson\ValueCategoryInterface as ValueCategoryService;
use App\Http\Requests\Lesson\QuestionRequest;

class QuestionController extends Controller
{
    protected $questionService, $questionTitleService;

    public function __construct(QuestionService $questionService, QuestionTitleService $questionTitleService, ValueCategoryService $valueCategoryService)
    {
        $this->questionService = $questionService;
        $this->questionTitleService = $questionTitleService;
        $this->valueCategoryService = $valueCategoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($questionTitleId, Request $request)
    {
        if(!$this->questionTitleService->find($questionTitleId)) return abort('404', 'uppss....');

        return inertia('Admin/Lesson/Question/Index', [
            'questions' => $this->questionService->getByQuestionTitlePaginatedWithParams($request, $questionTitleId),
            'questionTitle' => $this->questionTitleService->find($questionTitleId)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($questionTitleId)
    {
        if(!$this->questionTitleService->find($questionTitleId)) return abort('404', 'uppss....');

        $questionTitle = $this->questionTitleService->find($questionTitleId);
        $valueCategories = $this->valueCategoryService->findByField('category_id', $questionTitle->category_id);

        return inertia('Admin/Lesson/Question/Create', [
            'questionTitle' => $questionTitle,
            'valueCategories' => $valueCategories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($questionTitleId, QuestionRequest $request)
    {
        try {
            if(!$this->questionTitleService->find($questionTitleId)) return abort('404', 'uppss....');

            #store
            $this->questionService->create($request);

            #Bump....
            return redirect()->route('admin.question-titles.questions.index', $questionTitleId);

        } catch(\Exception $e) {
            #get message
            session()->flash('failed', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            #redirect
            return redirect()->back()->withInput($request->all());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($questionTitleId, $id)
    {
        if(!$this->questionTitleService->find($questionTitleId)) return abort('404', 'uppss....');

        if(!$this->questionService->find($id)) return abort('404', 'uppss....');

        $questionTitle = $this->questionTitleService->find($questionTitleId);
        $valueCategories = $this->valueCategoryService->findByField('category_id', $questionTitle->category_id);

        return inertia('Admin/Lesson/Question/Edit', [
            'questionTitle' => $questionTitle,
            'question' => $this->questionService->find($id),
            'valueCategories' => $valueCategories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($questionTitleId, QuestionRequest $request, $id)
    {
        try {
            if(!$this->questionTitleService->find($questionTitleId)) return abort('404', 'uppss....');

            if(!$this->questionService->find($id)) return abort('404', 'uppss....');

            #update
            $this->questionService->update($request, $id);

            #Bump....
            return redirect()->route('admin.question-titles.questions.index', $questionTitleId);

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
    public function destroy($questionTitleId, $id)
    {
        try {
            if(!$this->questionTitleService->find($questionTitleId)) return abort('404', 'uppss....');

            if(!$this->questionService->find($id)) return abort('404', 'uppss....');

            #delete
            $this->questionService->delete($id);

            #Bump....
            return redirect()->route('admin.question-titles.questions.index', $questionTitleId);

        } catch(\Exception $e) {
            #get message
            session()->flash('failed', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            #redirect
            return redirect()->back();
        }
    }
}
