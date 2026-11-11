<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\MasterData\CategoryInterface as CategoryService;
use App\Services\Contracts\Lesson\QuestionTitleInterface as QuestionTitleService;
use App\Http\Requests\MasterData\CategoryRequest;

class CategoryController extends Controller
{
    protected $categoryService, $questionTitleService;

    public function __construct(CategoryService $categoryService, QuestionTitleService $questionTitleService)
    {
        $this->categoryService = $categoryService;
        $this->questionTitleService = $questionTitleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/MasterData/Category/Index', [
            'categories' => $this->categoryService->getAllPaginatedWithParams($request)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Admin/MasterData/Category/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        try {
            #store
            $this->categoryService->create($request);

            #Bump....
            return redirect()->route('admin.categories.index');

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
        if(!$this->categoryService->find($id)) return abort('404', 'uppss....');

        return inertia('Admin/MasterData/Category/Edit', [
            'category' => $this->categoryService->find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        try {
            if(!$this->categoryService->find($id)) return abort('404', 'uppss....');

            #update
            $this->categoryService->update($request, $id);

            #Bump....
            return redirect()->route('admin.categories.index');

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
            $this->categoryService->delete($id);

            #Bump....
            return redirect()->route('admin.categories.index');

        } catch(\Exception $e) {
            #get message
            session()->flash('failed', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            #redirect
            return redirect()->back()->withInput($request->all());
        }
    }

    public function getQuestionTitle($categoryId)
    {
        return $this->questionTitleService->getAllByCategory($categoryId);
    }

    public function getValueCategory($categoryId)
    {
        return \App\Models\Lesson\ValueCategory::where('category_id', $categoryId)->orderBy('section', 'ASC')->get();
    }
}
