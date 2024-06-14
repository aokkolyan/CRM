<?php

namespace App\Http\Resources;

use App\Models\Source;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class SourceCollection extends ResourceCollection
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
                'create' => Auth::user()->can('create', Source::class),
                'import' => Auth::user()->can('import', Source::class),
                'export' => Auth::user()->can('export', Source::class),
            ]
        ];
    }
}
