<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductInformationResource extends JsonResource
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
            'brand' => $this->brand,
            'id' => $this->id,
            'colour' => json_decode($this->colour)->hex,
            'price' => floor($this->price / 10) * 10,
            'fit' => $this->fit,
            'rise' => $this->rise,
            'terrain' => $this->terrain
        ];
    }
}
