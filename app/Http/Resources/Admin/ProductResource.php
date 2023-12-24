<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//        return parent::toArray($request);
        return [
          'name' => $this->name,
          'image' => url(env('saved_product_images').$this->image),
          'category_id' => $this->category_id,
          'brand_id' => $this->brand_id,
          'price' => $this->price,
          'quantity' => $this->quantity,
          'galleries' => GalleryResource::collection($this->galleries),
        ];
    }
}
