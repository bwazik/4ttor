<?php

namespace App\Http\Controllers\Admin\Misc;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Traits\ValidatesExistence;
use App\Http\Controllers\Controller;
use App\Services\Admin\Misc\CategoryService;
use App\Http\Requests\Admin\Misc\CategoriesRequest;
use App\Traits\ServiceResponseTrait;

class CategoriesController extends Controller
{
    use ValidatesExistence, ServiceResponseTrait;

    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $categoriesQuery = Category::query()->select('id', 'name', 'slug', 'icon', 'description', 'order');

        if ($request->ajax()) {
            return $this->categoryService->getCategoriesForDatatable($categoriesQuery);
        }

        return view('admin.misc.categories.index');
    }


    public function insert(CategoriesRequest $request)
    {
        $result = $this->categoryService->insertCategory($request->validated());

        return $this->conrtollerJsonResponse($result);
    }

    public function update(CategoriesRequest $request)
    {
        $result = $this->categoryService->updateCategory($request->id, $request->validated());

        return $this->conrtollerJsonResponse($result);
    }

    public function delete(Request $request)
    {
        $this->validateExistence($request, 'categories');

        $result = $this->categoryService->deleteCategory($request->id);

        return $this->conrtollerJsonResponse($result);
    }

    public function deleteSelected(Request $request)
    {
        $this->validateExistence($request, 'categories');

        $result = $this->categoryService->deleteSelectedCategories($request->ids);

        return $this->conrtollerJsonResponse($result);
    }

}
