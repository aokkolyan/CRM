<?php

namespace App\Http\Resources;

use App\Models\Status;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class StatusCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
    public function with($request)
    {
        return [
            
            'can' => [
                'create' => Auth::user()->can('create', Status::class),
                'import' => Auth::user()->can('import', Status::class),
                'export' => Auth::user()->can('export', Status::class),
            ]
        ];
    }
}
