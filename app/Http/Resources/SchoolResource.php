<?php

namespace App\Http\Resources;

use App\Models\School;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class SchoolResource extends JsonResource
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
            'name_en' => $this->name_en,
            'name_kh' => $this->name_kh,
            'code' => $this->code,
            'description' => $this->description,
            'commission_types' => $this->commission_types,
            'point_multiplyier' => $this->point_multiplyier,
            'coupon_percentage' => $this->coupon_percentage,
            'is_riel' => ($this->is_riel),
            'can' => [
                'delete' => Auth::user()->can('delete', School::find($this->id)),
                'update' => Auth::user()->can('update', School::find($this->id)),
            ]
        ];
    }
}
