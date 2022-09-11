<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'brand' => $this->brand,
            'price' => $this->price,
            'id' => $this->id,
            'colour' => json_decode($this->colour)->index,
            'image' => $this->image

        ];
    }
}
