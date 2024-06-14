<?php

namespace App\Http\Resources;

use App\Models\PaymentStatus;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class PaymentStatusCollection extends ResourceCollection
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
                'create' => Auth::user()->can('create', PaymentStatus::class),
                'import' => Auth::user()->can('import', PaymentStatus::class),
                'export' => Auth::user()->can('export', PaymentStatus::class),
            ]
        ];
    }
}
