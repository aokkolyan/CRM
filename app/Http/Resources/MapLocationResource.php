<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;
class MapLocationResource extends JsonResource
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
            'title'=>$this->name, 
            'name'=> $this->name,   
            'id'=>$this->id,
            'icon_id'=>$this->mapIcon?$this->mapIcon->id:null,
            'icon'=>$this->mapIcon?$this->mapIcon->path:null,
            'position'=>[
                'lat'=> (float)$this->lat,
                'lng'=> (float)$this->long,
            ],
            'name_branch_manager'=>$this->name_branch_manager,
            'cultural_level'=>$this->cultural_level,
            'price_program' => $this->price_program,
            'specail_program'=> $this->specail_program,
            'address'=>$this->address,
            'qaunity_staff'=>$this->qaunity_staff,
            'quantity_student'=>$this->quantity_student,
            'social_media'=>$this->social_media,
            'other'=>$this->other,
            'branch_id'=>$this->branch_id,
            
            'branch_code'=>$this->branch->code,
            'is_competitor'=>$this->is_competitor,
            'programs'=>$this->programs,
            
            
            
        ];
    }
}
