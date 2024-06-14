<?php

namespace App\Http\Resources;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class ProductCollection extends ResourceCollection
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
            'categories'=>Category::geT(),
            'can' => [
                'create' => Auth::user()->can('create', Product::class),
                'import' => Auth::user()->can('import', Product::class),
                'export' => Auth::user()->can('export', Product::class),
            ]
        ];
    }
}
