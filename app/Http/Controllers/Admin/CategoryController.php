<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Resources\Admin\CategoryResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::denies('read-category')){
            return $this->errorResponse(403,'not permission user');
        }
        $category = Category::paginate(10);
        return $this->successResponse(200,[
            'categories' => CategoryResource::collection($category),
            'links' => CategoryResource::collection($category)->response()->getData()->links,
        ],'getCategories');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Category $category)
    {
        $validate = Validator::make($request->all(),[
            'title' => 'unique:categories,title|required',
        ]);
        if($validate->fails()){
            return $this->errorResponse(422,$validate->messages());
        }
        $category->newCategory($request);
        $dataResponse = $category->orderBy('id','desc')->first();
        return $this->successResponse(200,new CategoryResource($dataResponse),'category created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function parent(Category $category)
    {
        return $this->successResponse(200,new CategoryResource($category->load('parent')));
    }
    
    public function children(Category $category)
    {
        return $this->successResponse(200,new CategoryResource($category->load('children')));
    }

    public function getProducts(Category $category)
    {
        return $this->successResponse(200,new CategoryResource($category->load('products')),'getProducts');
    }
}
