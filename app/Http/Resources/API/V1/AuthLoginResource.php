<?php

namespace App\Http\Resources\API\V1;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class AuthLoginResource
 * @package App\Http\Resources\API\V1
 */
class AuthLoginResource extends JsonResource
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
            'token'         => $this->resource,
            'userId'        => auth()->user()->id,
            'name'          => auth()->user()->first_name,
            'email'         => auth()->user()->email,
        ];
    }
}
