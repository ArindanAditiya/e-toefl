<?php

namespace App\Services\Contracts\Transaction;

interface BankInterface
{
    public function find($id);

    public function create($request);

    public function update($request, $id);

    public function delete($id);
}
