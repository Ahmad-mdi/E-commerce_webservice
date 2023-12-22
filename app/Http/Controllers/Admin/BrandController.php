<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BrandEditRequest;
use App\Http\Requests\Admin\BrandNewRequest;
use App\Http\Resources\Admin\BrandResource;
use App\Models\Brand;
use App\Services\Admin\BrandService;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    private BrandService $service;

    public function __construct(BrandService $service)
    {
        $this->service = $service;
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        return $this->service->listOfBrands();
    }


    public function store(BrandNewRequest $request): \Illuminate\Http\JsonResponse
    {
        $created = $this->service->addBrand($request);
        return $this->successResponse(201, new BrandResource($created), 'brand added successfully');
    }

    public function show(string $id)
    {
        //
    }

    public function update(BrandEditRequest $request, Brand $brand): \Illuminate\Http\JsonResponse
    {
        if ($brand->uniqueTitle($request)) {
            return $this->errorResponse(422, 'the title has already been taken');
        }
        $this->service->editBrand($request, $brand);
        return $this->successResponse(201, new BrandResource($brand), 'brand edited successfully');
    }

    public function destroy(Brand $brand): \Illuminate\Http\JsonResponse
    {
        $this->service->deleteBrand($brand);
        return $this->successResponse(201, new BrandResource($brand), 'brand deleted successfully');
    }
}
