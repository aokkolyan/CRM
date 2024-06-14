<?php

namespace App\Http\Resources;

use App\Models\Branch;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class BranchResource extends JsonResource
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
            'school_name' => $this->school?$this->school->name_en:'',
            'school_id' => $this->school_id,
            'name_en' => $this->name_en,
            'name_kh' => $this->name_kh,
            'commission' => $this->commission,
            'max_discount' => $this->max_discount,
            'code' => $this->code,
            'description' => $this->description,
            'is_riel' => $this->is_riel,
            'displayText' => ($this->school?$this->school->code:'').'-'.$this->code,
        ];
    }
}
