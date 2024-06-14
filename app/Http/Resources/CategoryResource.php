<?php

namespace App\Http\Resources;


use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CategoryResource extends JsonResource
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
            'can' => [
                'delete' => Auth::user()->can('delete', Category::find($this->id)),
                'update' => Auth::user()->can('update', Category::find($this->id)),
            ]
        ];
    }
}
