<?php

namespace App\Services\MasterData;

use App\Repositories\Contracts\MasterData\CategoryInterface as CategoryRepo;
use App\Services\Contracts\MasterData\CategoryInterface;
use App\Models\MasterData\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Auth;
use App\Traits\Uploadable;

class CategoryService implements CategoryInterface
{
    use Uploadable;

    protected $image_path = 'upload_files/categories';

    protected $categoryRepo;

    public function __construct(CategoryRepo $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function all($columns = ['*'])
    {
        return $this->categoryRepo->all($columns);
    }

    public function getAllSimplePaginatedWithParams($params, $limit = 10)
    {
       return $this->categoryRepo->getAllSimplePaginatedWithParams($params, $limit);
    }

    public function getAllPaginatedWithParams($params, $limit = 10)
    {
       return $this->categoryRepo->getAllPaginatedWithParams($params, $limit);
    }

     /**
     * Find data by id
     *
     * @param $id
     * @param array $columns
     *
     * @return mixed
     */
    public function find($id)
    {
        return $this->categoryRepo->find($id);
    }

    /**
     * @param $request
     *
     * @return mixed
     * @throws \Throwable
     */
    public function create($request)
    {
        $permissions = DB::transaction(function () use ($request) {
            $input = $request->all();
            if($request->hasFile('thumbnail')){
                $file = $request->file('thumbnail')->getClientOriginalName();
                $filename = pathinfo($file, PATHINFO_FILENAME);

                $filename = $this->uploadFile($request->file('thumbnail'), $filename, $this->image_path);
                $input['thumbnail'] = $filename;
            }

            return $this->categoryRepo->create($input);
        });

        return $permissions;
    }

     /**
     * @param $id
     * @param $request
     *
     * @return mixed
     * @throws \Throwable
    */
    public function update($request, $id)
    {
        $permissions = DB::transaction(function () use ($request, $id) {
            $input = $request->except('_token','_method');
            $category = $this->find($id);

            if($request->hasFile('thumbnail')){
                $this->deleteFile($category->thumbnail, $this->image_path);
                $file = $request->file('thumbnail')->getClientOriginalName();
                $filename = pathinfo($file, PATHINFO_FILENAME);
                $filename = $this->uploadFile($request->file('thumbnail'), $filename, $this->image_path);
                $input['thumbnail'] = $filename;
            } else {
                $input['thumbnail'] = $category->thumbnail;
            }
            return $this->categoryRepo->update($input, $id);
        });

        return $permissions;
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function delete($id)
    {
        $category = $this->find($id);
        $this->deleteFile($category->thumbnail, $this->image_path);
        return $this->categoryRepo->delete($id);
    }
}
