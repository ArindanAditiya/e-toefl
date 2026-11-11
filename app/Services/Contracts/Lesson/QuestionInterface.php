<?php

namespace App\Services\Contracts\Lesson;

interface QuestionInterface
{
    public function find($id);

    public function create($request);

    public function update($request, $id);

    public function delete($id);
}
