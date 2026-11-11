<?php

namespace App\Http\Controllers\User\TryOut;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Contracts\MasterData\CategoryInterface as CategoryService;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return inertia('User/TryOut/Category/Index', [
            'categories' => $this->categoryService->all()
        ]);
    }
}
