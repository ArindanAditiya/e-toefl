<?php

namespace App\Http\Controllers\Admin\Transaction;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\Transaction\TransactionInterface as TransactionService;
use App\Services\Contracts\Transaction\BankInterface as BankService;

class TransactionController extends Controller
{
    protected $transactionService, $bankService;

    public function __construct(TransactionService $transactionService, BankService $bankService)
    {
        $this->transactionService = $transactionService;
        $this->bankService = $bankService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return inertia('Admin/Transaction/Transaction/Index', [
            'transactions' => $this->transactionService->getAllPaginatedWithParams($request)
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(!$this->transactionService->find($id)) return abort('404', 'uppss....');

        return inertia('Admin/Transaction/Transaction/Show', [
            'transaction' => $this->transactionService->find($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            if(!$this->transactionService->find($id)) return abort('404', 'uppss....');

            #update
            $this->transactionService->update($request, $id);

            #Bump....
            return redirect()->route('admin.transactions.show', $transaction->id);

        } catch(\Exception $e) {
            #get message
            session()->flash('failed', $e->getMessage() . ' in file :'.$e->getFile() .' line: '. $e->getLine());

            #redirect
            return redirect()->back()->withInput($request->all());
        }
    }

    public function invoice($id)
    {
        if(!$this->transactionService->find($id)) return abort('404', 'uppss....');

        return inertia('Admin/Transaction/Transaction/Invoice', [
            'transaction' => $this->transactionService->find($id),
            'banks' => $this->bankService->all()
        ]);
    }
}
