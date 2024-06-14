<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class ParentReferralResource extends JsonResource
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
            'parent_name' => $this->parent_name,
            'parent2_name' => $this->parent2_name,
            'phone' => $this->phone ,
            'phone2' => $this->phone2 ,
            'creator_phone'=>$this->referrals[0]->creator->phone,
            'referrals' => new ReferralCollection($this->referrals),
            'created_at' => $this->created_at->diffForHumans(),
        ];
    }
}
