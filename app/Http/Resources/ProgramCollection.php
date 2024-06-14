<?php

namespace App\Http\Resources;

use App\Models\Program;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class ProgramCollection extends ResourceCollection
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
                'create' => Auth::user()->can('create', Program::class),
                'import' => Auth::user()->can('import', Program::class),
                'export' => Auth::user()->can('export', Program::class),
            ]
        ];
    }
}
