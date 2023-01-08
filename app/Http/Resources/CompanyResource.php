<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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

            'id'=>$this->id,
            'cover_picture'=>$this->cover_picture,
            'logo'=>$this->logo,
            'name'=>$this->name,
            'address'=>$this->address,
            'lat'=>$this?->lat,
            'long'=>$this?->long,
            'phone'=>$this->phone,
            'description'=>$this->description,
            'short_desc'=>$this->short_desc,
            'category_id'=>$this?->category->id,
            'category_name'=>$this?->category->name,
            // 'category'=> new SimpleCategoryResource($this?->category),
            'images'=> ImageResource::collection($this?->images),
        ];
    }
}
