<?php

namespace App\Repositories\Lesson;

use App\Models\Lesson\ValueCategory;
use App\Repositories\Contracts\Lesson\ValueCategoryInterface;
use App\Repositories\BaseRepository;

class ValueCategoryRepository extends BaseRepository implements ValueCategoryInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct(ValueCategory $valueCategory)
    {
        $this->model = $valueCategory->whereNotNull('id');
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 10)
    {
        $valueCategories = $this->model;
        if(isset($params->search) && !empty($params->search)) $valueCategories->where('name', 'like', '%' . $params->search . '%');
        $valueCategories = $valueCategories->orderBy('created_at', 'ASC')->simplePaginate($limit);
        return $valueCategories;
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $valueCategories = $this->model;
        if(isset($params->search) && !empty($params->search)) $valueCategories->where('name', 'like', '%' . $params->search . '%');
        $valueCategories = $valueCategories->with(['category'])->orderBy('created_at', 'ASC')->paginate($limit);
        return $valueCategories;
    }
}
