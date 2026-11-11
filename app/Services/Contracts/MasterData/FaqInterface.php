<?php

namespace App\Services\Contracts\MasterData;

interface FaqInterface
{
    public function find($id);

    public function create($request);

    public function update($request, $id);

    public function delete($id);
}
