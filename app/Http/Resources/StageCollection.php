<?php

namespace App\Http\Resources;

use App\Models\Stage;
use App\Models\Status;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class StageCollection extends ResourceCollection
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
            'statuses'=>Status::get(),
            'can' => [
                'create' => Auth::user()->can('create', Stage::class),
                'import' => Auth::user()->can('import', Stage::class),
                'export' => Auth::user()->can('export', Stage::class),
            ]
        ];
    }
}
