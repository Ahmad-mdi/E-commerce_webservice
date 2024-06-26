<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
            'title' => $this->title,
            'parent' => new CategoryResource($this->whenLoaded('parent')) ,
            'children' => CategoryResource::collection($this->whenLoaded('children')) ,
            'products' => ProductResource::collection($this->whenLoaded('products')) ,
        ];
    }
}
