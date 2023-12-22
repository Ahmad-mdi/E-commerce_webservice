<?php

namespace App\Services\Admin;

use App\Http\Resources\Admin\BrandResource;
use App\Models\Brand;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BrandService
{
    use ApiResponse;

    public function listOfBrands(): \Illuminate\Http\JsonResponse
    {
        $list = Brand::all();
        return $this->successResponse(200, BrandResource::collection($list), 'listOfBrands');
    }

    public function addBrand(Request $request): \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
    {
        $fileName = Carbon::now()->microsecond . '.' . $request->image->extension();
        $request->image->storeAs('image-brands', $fileName, 'public');
        return Brand::query()->create([
            'title' => $request->title,
            'image' => $fileName,
        ]);
    }

    public function editBrand(Request $request, Brand $brand): bool
    {
        if ($request->has('image')) {
            $fileName = Carbon::now()->microsecond . '.' . $request->image->extension();
            $request->image->storeAs('image-brands', $fileName, 'public');
        }
        return $brand->update([
            'title' => $request->title,
            'image' => $request->has('image') ? $fileName : $brand->image,
        ]);
    }

    public function deleteBrand(Brand $brand): ?bool
    {
        return $brand->delete();
    }
}
