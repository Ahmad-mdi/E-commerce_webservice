<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Middleware\CheckPermission;
use App\Http\Resources\Admin\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Gate;

class ProductController extends ApiController
{
    
   /*  public function __construct()
    {
        return $this->middleware(CheckPermission::class . ':create-product')
            ->only('store');
    } */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Gate::denies('read-product')){
            return $this->errorResponse(403,'not permission user!');
        }
        $product = Product::paginate(10);
        return $this->successResponse(200,[
            'products' => ProductResource::collection($product),
            'links' => ProductResource::collection($product)->response()->getData()->links,
        ],'get products');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Product $product)
    {
        $validate = Validator::make($request->all(),[
            'category_id' => 'exists:categories,id|required',
            'brand_id' => 'exists:brands,id|required',
            'name' => 'required',
            'image' => 'image|mimes:jpg,svg,png,jpeg|required',
            'description' => 'required|string',
            'slug' => 'required|unique:products,slug',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
        ]);
        if($validate->fails()){
            return $this->errorResponse(422,$validate->messages());
        }
        $product->newProduct($request);
        $dataResponse = $product->orderBy('id','desc')->first();
        return $this->successResponse(200,new ProductResource($dataResponse),'product created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $this->successResponse(200,new ProductResource($product),'GET'.'-'.$product->name);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $validate = Validator::make($request->all(),[
            'category_id' => 'exists:categories,id|required',
            'brand_id' => 'exists:brands,id|required',
            'name' => 'required',
            'image' => 'image|mimes:jpg,svg,png,jpeg',
            'description' => 'required|string',
            'slug' => 'required|unique:products,slug',
            'price' => 'required|integer',
            'quantity' => 'required|integer',
        ]);
        if($validate->fails()){
            return $this->errorResponse(422,$validate->messages());
        }
        $slugUnique = Product::query()
        ->where('slug',$request->slug)
        ->where('id','!=', $product->id)
        ->exists();
        if($slugUnique){
            return $this->errorResponse(401,'the slug invalid!');
        }
        $product->updateProduct($request);
        return $this->successResponse(200,new ProductResource($product),$product->name.' '.'updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return $this->successResponse(200,new ProductResource($product),$product->name.' '.'deleted successfully');
    }
}
