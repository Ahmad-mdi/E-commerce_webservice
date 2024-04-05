<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Admin\GalleryCreateRequest;
use App\Http\Resources\Admin\GalleryResource;
use App\Models\Gallery;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GalleryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        return $this->successResponse(200,GalleryResource::collection($product->galleries),'images for product');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GalleryCreateRequest $request,Product $product)
    {
        $product->newgallery($request);
        return $this->successResponse(201,true,'Done!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Gallery  $gallery
     * @return \Illuminate\Http\Response
     */
    public function show(Gallery $gallery)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Galleries  $galleries
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Gallery $gallery)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Galleries  $galleries
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product,Gallery $gallery)
    {
        $product->deleteGallery($gallery);
        return $this->successResponse(200,true,'image deleted successfully');
    }
}
