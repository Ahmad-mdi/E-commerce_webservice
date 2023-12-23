<?php

namespace App\Services\Admin;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProductService {
    public function listOfProducts()
    {
        return Product::paginate(20);
    }

    public function addProduct(Request $request): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
    {
        $fileName = Carbon::now()->microsecond.'.'.$request->image->extension();
        $request->image->storeAs('imagePrimary-products',$fileName,'public');
        return Product::query()->create([
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'name' => $request->name,
            'slug' => $request->slug,
            'desc' => $request->desc,
            'image' => $fileName,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);
    }

    public function showById(Product $product): Product
    {
        return $product;
    }

    public function editProduct(Request $request,Product $product): bool
    {
        if ($request->has('image')) {
            $fileName = Carbon::now()->microsecond.'.'.$request->image->extension();
            $request->image->storeAs('imagePrimary-products',$fileName,'public');
        }
        return $product->update([
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'name' => $request->name,
            'slug' => $request->slug,
            'desc' => $request->desc,
            'image' => $request->has('image') ? $fileName : $product->image,
            'price' => $request->price,
            'quantity' => $request->quantity,
        ]);
    }
}
