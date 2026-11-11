<?php

namespace App\Services\Contracts\Exam;

interface ExamInterface
{
    public function find($id);

    public function create($request);

    public function update($request, $id);

    public function delete($id);
}
