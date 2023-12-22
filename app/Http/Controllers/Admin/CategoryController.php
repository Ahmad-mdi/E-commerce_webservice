<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryEditRequest;
use App\Http\Requests\Admin\CategoryNewRequest;
use App\Http\Resources\Admin\CategoryResource;
use App\Models\Category;
use App\Services\Admin\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private CategoryService $service;
    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $list = $this->service->listOfCategories();
        return $this->successResponse(200, CategoryResource::collection($list), 'listOfCategories');
    }


    public function store(CategoryNewRequest $request): \Illuminate\Http\JsonResponse
    {
        $created = $this->service->addCategory($request);
        return $this->successResponse(200,
            new CategoryResource($created), 'category added successfully');
    }


    public function show(Category $category): \Illuminate\Http\JsonResponse
    {
        $id = $this->service->getById($category);
        return $this->successResponse(200, new CategoryResource($id));
    }


    public function update(CategoryEditRequest $request, Category $category): \Illuminate\Http\JsonResponse
    {
        if ($category->uniqueTitle($request)) {
            return $this->errorResponse(422, 'the title has already been taken');
        }
        $this->service->editCategory($request, $category);
        return $this->successResponse(201, new CategoryResource($category), 'category edited successfully');
    }


    public function destroy(Category $category): \Illuminate\Http\JsonResponse
    {
        $this->service->deleteCategory($category);
        return $this->successResponse(201,new CategoryResource($category),'category deleted successfully');
    }

    public function parent(Category $category): \Illuminate\Http\JsonResponse
    {
        $parent = $this->service->showParent($category);
        return $this->successResponse(201,new CategoryResource($parent),'get parent success');
    }

    public function children(Category $category): \Illuminate\Http\JsonResponse
    {
        $children = $this->service->showChildren($category);
        return $this->successResponse(201,new CategoryResource($children),'get children success');
    }
}
