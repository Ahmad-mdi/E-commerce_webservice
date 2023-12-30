<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
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
          'title' => $this->title,
//          'parent_id' => $this->whenLoaded('parent'),
          'parent' => new CategoryResource($this->whenLoaded('parent')),
          'children' => CategoryResource::collection($this->whenLoaded('children')),
          'products' => ProductResource::collection($this->whenLoaded('products')),
        ];
    }
}
