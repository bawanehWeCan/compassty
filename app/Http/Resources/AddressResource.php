<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'code'=> $this->code?->code,
            'icon_id'=>$this->icon_id,
            'icon'=>$this?->icon?->icon,
            'name'=>$this->name,
            'lat'=>$this->lat,
            'long'=>$this->long,
            'country_id'=>$this->country?->id,
            'city_id'=>$this->city?->id,
            'country'=>$this->country?->name,
            'city'=>$this->city?->name,
            'street'=>$this->street,
            'build_number'=>$this->build_number,
            'house_number'=>$this->house_number,
            'floor_number'=>$this->floor_number,
            'note'=>$this->note,
            'region'=>$this->region,
            'phone_number'=>$this->phone_number,
            'user'=> new UserResource($this?->user),
            'images'=>ImageResource::collection( $this->images ),
            // 'code'=> new CodeResource($this?->code),

        ];
    }
}
