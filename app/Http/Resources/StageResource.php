<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Stage;
use Illuminate\Support\Facades\Auth;
class StageResource extends JsonResource
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
            'status' => $this->status,
            'can' => [
                'delete' => Auth::user()->can('delete', Stage::find($this->id)),
                'update' => Auth::user()->can('update', Stage::find($this->id)),
            ]
        ];
    }
}
