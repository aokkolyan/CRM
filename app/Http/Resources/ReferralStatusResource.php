<?php

namespace App\Http\Resources;

use App\Models\ReferralStatus;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ReferralStatusResource extends JsonResource
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
            'color' => $this->color,
            'can' => [
                'delete' => Auth::user()->can('delete', ReferralStatus::find($this->id)),
                'update' => Auth::user()->can('update', ReferralStatus::find($this->id)),
            ]
        ];
    }
}
