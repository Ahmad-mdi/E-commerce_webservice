<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductEditRequest;
use App\Http\Requests\Admin\ProductNewRequest;
use App\Http\Resources\Admin\ProductResource;
use App\Models\Product;
use App\Services\Admin\ProductService;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    private ProductService $service;
    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $list = $this->service->listOfProducts();
        return $this->successResponse(200,[
            'products' => ProductResource::collection($list),
            'links' => ProductResource::collection($list)->response()->getData()->links,
            'meta' => ProductResource::collection($list)->response()->getData()->meta,
        ],'listOfProducts');
    }


    public function store(ProductNewRequest $request): \Illuminate\Http\JsonResponse
    {
        $dataList = $this->service->addProduct($request);
        return $this->successResponse(201,$dataList,'product added successfully');
    }


    public function show(Product $product): \Illuminate\Http\JsonResponse
    {
        return $this->successResponse('201',new ProductResource($product),'getProduct');
    }


    public function update(ProductEditRequest $request, Product $product): \Illuminate\Http\JsonResponse
    {
        if ($product->uniqueSlug($request)) {
            return $this->errorResponse(422,'the slug already been taken');
        }
         $this->service->editProduct($request,$product);
        return $this->successResponse(201,new ProductResource($product),'product edited successfully');
    }

    public function destroy(string $id)
    {
        //
    }
}
