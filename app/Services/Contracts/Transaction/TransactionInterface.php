<?php

namespace App\Services\Contracts\Transaction;

interface TransactionInterface
{
    public function find($id);

    public function create($request);

    public function update($request, $id);

    public function delete($id);
}
