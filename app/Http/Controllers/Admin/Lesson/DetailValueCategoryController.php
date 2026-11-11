<?php

namespace App\Http\Controllers\Admin\Lesson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\Lesson\DetailValueCategoryInterface as DetailValueCategoryService;
use App\Services\Contracts\Lesson\ValueCategoryInterface as ValueCategoryService;
use App\Http\Requests\Lesson\DetailValueCategoryRequest;

class DetailValueCategoryController extends Controller
{
    protected $detailValueCategoryService, $valueCategoryService;

    public function __construct(DetailValueCategoryService $detailValueCategoryService, ValueCategoryService $valueCategoryService)
    {
        $this->detailValueCategoryService = $detailValueCategoryService;
        $this->valueCategoryService = $valueCategoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($valueCategoryId, Request $request)
    {
        if(!$this->valueCategoryService->find($valueCategoryId)) return abort('404', 'uppss....');

        return inertia('Admin/Lesson/DetailValueCategory/Index', [
            'valueCategory' => $this->valueCategoryService->find($valueCategoryId),
            'detailValueCategories' => $this->detailValueCategoryService->getAllByValueCategoryPaginatedWithParams($valueCategoryId, $request)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($valueCategoryId)
    {
        if(!$this->valueCategoryService->find($valueCategoryId)) return abort('404', 'uppss....');

        return inertia('Admin/Lesson/DetailValueCategory/Create', [
            'valueCategory' => $this->valueCategoryService->find($valueCategoryId)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($valueCategoryId, DetailValueCategoryRequest $request)
    {
        try {
            if(!$this->valueCategoryService->find($valueCategoryId)) return abort('404', 'uppss....');

            #store
            $this->detailValueCategoryService->create($request);

            #Bump....
            return redirect()->route('admin.value-categories.detail-value-categories.index', $valueCategoryId);

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
    public function edit($valueCategoryId, $id)
    {
        if(!$this->valueCategoryService->find($valueCategoryId)) return abort('404', 'uppss....');

        if(!$this->detailValueCategoryService->find($id)) return abort('404', 'uppss....');

        return inertia('Admin/Lesson/DetailValueCategory/Edit', [
            'valueCategory' => $this->valueCategoryService->find($valueCategoryId),
            'detailValueCategory' => $this->detailValueCategoryService->find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($valueCategoryId, DetailValueCategoryRequest $request, $id)
    {
        try {
            if(!$this->valueCategoryService->find($valueCategoryId)) return abort('404', 'uppss....');

            if(!$this->detailValueCategoryService->find($id)) return abort('404', 'uppss....');

            #update
            $this->detailValueCategoryService->update($request, $id);

            #Bump....
            return redirect()->route('admin.value-categories.detail-value-categories.index', $valueCategoryId);

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
    public function destroy($valueCategoryId, $id)
    {
        try {
            if(!$this->valueCategoryService->find($valueCategoryId)) return abort('404', 'uppss....');
            if(!$this->detailValueCategoryService->find($id)) return abort('404', 'uppss....');

            #delete
            $this->detailValueCategoryService->delete($id);

            #Bump....
            return redirect()->route('admin.value-categories.detail-value-categories.index', $valueCategoryId);

        } catch(\Exception $e) {
            #get message
            session()->flash('failed', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            #redirect
            return redirect()->back();
        }
    }
}
