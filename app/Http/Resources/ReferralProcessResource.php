<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReferralProcessResource extends JsonResource
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
            'remark' => $this->remark,
            'creator' => $this->creator->name,
            // 'created_at' => $this->created_at->diffForHumans( null,false,true),
            'created_at' => $this->created_at,
        ];
    }
}
