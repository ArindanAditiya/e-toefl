<?php

namespace App\Http\Controllers\Admin\Lesson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\Lesson\QuestionTitleInterface as QuestionTitleService;
use App\Services\Contracts\MasterData\CategoryInterface as CategoryService;
use App\Http\Requests\Lesson\QuestionTitleRequest;

class QuestionTitleController extends Controller
{
    protected $questionTitleService, $categoryService;

    public function __construct(QuestionTitleService $questionTitleService, CategoryService $categoryService)
    {
        $this->questionTitleService = $questionTitleService;
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/Lesson/QuestionTitle/Index', [
            'questionTitles' => $this->questionTitleService->getAllPaginatedWithParams($request)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Admin/Lesson/QuestionTitle/Create', [
            'categories' => $this->categoryService->all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionTitleRequest $request)
    {
        try {
            #store
            $this->questionTitleService->create($request);

            #Bump....
            return redirect()->route('admin.question-titles.index');

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
    public function edit($id)
    {
        if(!$this->questionTitleService->find($id)) return abort('404', 'uppss....');

        return inertia('Admin/Lesson/QuestionTitle/Edit', [
            'questionTitle' => $this->questionTitleService->find($id),
            'categories' => $this->categoryService->all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionTitleRequest $request, $id)
    {
        try {
            if(!$this->questionTitleService->find($id)) return abort('404', 'uppss....');

            #update
            $this->questionTitleService->update($request, $id);

            #Bump....
            return redirect()->route('admin.question-titles.index');

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
            $this->questionTitleService->delete($id);

            #Bump....
            return redirect()->route('admin.question-titles.index');

        } catch(\Exception $e) {
            #get message
            session()->flash('failed', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            #redirect
            return redirect()->back()->withInput($request->all());
        }
    }
}
