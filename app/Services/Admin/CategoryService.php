<?php

namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryService
{
    public function listOfCategories(): \Illuminate\Database\Eloquent\Collection
    {
//        return Category::all()->load('parent');
        return Category::all();
    }

    public function addCategory(Request $request): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        return Category::query()->create([
            'title' => $request->title,
            'parent_id' => $request->parent_id,
        ]);
    }

    public function getById(Category $category): Category
    {
        return $category;
    }

    public function editCategory(Request $request, Category $category): bool
    {
        return $category->update([
            'title' => $request->title,
            'parent_id' => $request->parent_id,
        ]);
    }

    public function deleteCategory(Category $category): ?bool
    {
        return $category->delete();
    }

    public function showParent(Category $category): Category
    {
        return $category->load('parent');
    }
    public function showChildren(Category $category): Category
    {
        return $category->load('children');
    }
}
