<?php

namespace App\Repositories\MasterData;

use App\Models\MasterData\Category;
use App\Repositories\Contracts\MasterData\CategoryInterface;
use App\Repositories\BaseRepository;

class CategoryRepository extends BaseRepository implements CategoryInterface
{
    /**
     * @var
     */
    protected $model;

    public function __construct(Category $category)
    {
        $this->model = $category->whereNotNull('id');
    }

    public function all($columns = ['*'])
    {
        return $this->model->orderBy('created_at', 'ASC')->get($columns);
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 10)
    {
        $categories = $this->model;
        if(isset($params->search) && !empty($params->search)) $categories->where('name', 'like', '%' . $params->search . '%');
        $categories = $categories->orderBy('created_at', 'ASC')->simplePaginate($limit);
        return $categories;
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
        $categories = $this->model;
        if(isset($params->search) && !empty($params->search)) $categories->where('name', 'like', '%' . $params->search . '%');
        $categories = $categories->orderBy('created_at', 'ASC')->paginate($limit);
        return $categories;
    }
}
