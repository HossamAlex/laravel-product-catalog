<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'special_price' => $this->special_price,
            'image' => $this->getFirstMediaUrl('main_image'),
            'gallery' => $this->getMedia('gallery')->map(fn ($media) => $media->getUrl()),
            'videos' => $this->getMedia('videos')->map(fn ($media) => $media->getUrl()),
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'attributes' => $this->whenLoaded('attributeValues', fn () => $this->attributeValues),
            'breadcrumbs' => $this->categories
                            ->first()
                             ?? [],
            ];
    }
}
