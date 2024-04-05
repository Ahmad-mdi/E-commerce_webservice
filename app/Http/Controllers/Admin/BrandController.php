<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BrandController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brand = Brand::all();
        return $this->successResponse(200,BrandResource::collection($brand),'getBrandOk');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Brand $brand)
    {
        $validate = Validator::make($request->all(),[
            'title' => 'required|string|unique:brands,title',
            'image' => 'required|image',
        ]);
        if($validate->fails()){
            return $this->errorResponse(422,$validate->messages());
        }
        $brand->newBrand($request);
        $dataResponse = $brand->orderBy('id','desc')->first();
        return $this->successResponse(201,new BrandResource($dataResponse),'brand created successFully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        return $this->successResponse(200,new BrandResource($brand),'GET'.'-'.$brand->title);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Brand $brand)
    {
        $validate = Validator::make($request->all(),[
            'title' => 'required|string|unique:brands,title',
            'image' => 'image',
        ]);
        if($validate->fails()){
            return $this->errorResponse(422,$validate->messages());
        }
        $brandUnique = Brand::query()
            ->where('title', $request->title)
            ->where('id', '!=', $brand->id)
            ->exists();

        if ($brandUnique) {
            return  $this->errorResponse(400,'The title has already been taken');
        }
        $brand->updateBrand($request);
        return $this->successResponse(200,new BrandResource($brand),'brand'.'-'.$brand->title.' '.'updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        $brand->deleteBrand($brand);
        return $this->successResponse(200,$brand->title.' '.'deleted successfully');
    }

    public function getProducts(Brand $brand)
    {
        return $this->successResponse(200,new BrandResource($brand->load('products')),'getProducts');
    }
}
