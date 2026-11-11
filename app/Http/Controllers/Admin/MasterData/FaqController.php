<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\MasterData\FaqInterface as FaqService;
use App\Http\Requests\MasterData\FaqRequest;

class FaqController extends Controller
{
    protected $faqService;

    public function __construct(FaqService $faqService)
    {
        $this->faqService = $faqService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/MasterData/Faq/Index', [
            'faqs' => $this->faqService->getAllPaginatedWithParams($request)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Admin/MasterData/Faq/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FaqRequest $request)
    {
        try {
            #store
            $this->faqService->create($request);

            #Bump....
            return redirect()->route('admin.faqs.index');

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
        if(!$this->faqService->find($id)) return abort('404', 'uppss....');

        return inertia('Admin/MasterData/Faq/Show', [
            'faq' => $this->faqService->find($id),
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
        if(!$this->faqService->find($id)) return abort('404', 'uppss....');

        return inertia('Admin/MasterData/Faq/Edit', [
            'faq' => $this->faqService->find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FaqRequest $request, $id)
    {
        try {
            if(!$this->faqService->find($id)) return abort('404', 'uppss....');

            #update
            $this->faqService->update($request, $id);

            #Bump....
            return redirect()->route('admin.faqs.index');

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
            $this->faqService->delete($id);

            #Bump....
            return redirect()->route('admin.faqs.index');

        } catch(\Exception $e) {
            #get message
            session()->flash('failed', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            #redirect
            return redirect()->back()->withInput($request->all());
        }
    }
}
