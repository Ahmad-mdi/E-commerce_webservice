<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'brand_id' => $this->brand_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => url(env('PRODUCT_IMAGE_UPLOAD_PATH').$this->image),
            'price' => $this->price,
            'delivery_amount' => $this->delivery_amount,
            'description' => $this->description,
            'quantity' => $this->quantity,
            'galleries' => GalleryResource::collection($this->galleries),
        ];
    }
}
