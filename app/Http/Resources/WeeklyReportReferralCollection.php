<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Controllers\ApiResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\WeeklyReportReferral;


class WeeklyReportReferralCollection extends ResourceCollection
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
            'can'=>[
                'create'=>Auth::user()->can('create',WeeklyReportReferral::class),
                'update'=>Auth::user()->can('update',WeeklyReportReferral::class),
                'pay'=>Auth::user()->can('pay',WeeklyReportReferral::class),
            ]
        ];
    }
}
