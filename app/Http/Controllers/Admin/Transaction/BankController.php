<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\Transaction\BankInterface as BankService;
use App\Http\Requests\Transaction\BankRequest;

class BankController extends Controller
{
    protected $bankService;

    public function __construct(BankService $bankService)
    {
        $this->bankService = $bankService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/Transaction/Bank/Index', [
            'banks' => $this->bankService->getAllPaginatedWithParams($request)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return inertia('Admin/Transaction/Bank/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankRequest $request)
    {
        try {
            #store
            $this->bankService->create($request);

            #Bump....
            return redirect()->route('admin.banks.index');

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
        if(!$this->bankService->find($id)) return abort('404', 'uppss....');

        return inertia('Admin/Transaction/Bank/Edit', [
            'bank' => $this->bankService->find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BankRequest $request, $id)
    {
        if(!$this->bankService->find($id)) return abort('404', 'uppss....');

        #update
        $this->bankService->update($request, $id);

        #Bump....
        return redirect()->route('admin.banks.index');

        try {
            if(!$this->bankService->find($id)) return abort('404', 'uppss....');

            #update
            $this->bankService->update($request, $id);

            #Bump....
            return redirect()->route('admin.banks.index');

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
            $this->bankService->delete($id);

            #Bump....
            return redirect()->route('admin.banks.index');

        } catch(\Exception $e) {
            #get message
            session()->flash('failed', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            #redirect
            return redirect()->back()->withInput($request->all());
        }
    }
}
