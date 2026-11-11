<?php

namespace App\Services\Contracts\User;

interface UserInterface
{
    public function find($id);

    public function create($request);

    public function update($request, $id);

    public function delete($id);
}
