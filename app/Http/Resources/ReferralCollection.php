<?php

namespace App\Http\Resources;

use App\Models\Referral;
use App\Models\Branch;
use App\Models\Program;
use App\Models\Product;
use App\Models\School;
use App\Models\User;
use App\Models\ReferralStatus;
use App\Models\PaymentStatus;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;

class ReferralCollection extends ResourceCollection
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
            // 'branches'=>Auth::user()->branch->school_id==1?Branch::where('school_id','!=',1)->get():Branch::where('id',Auth::user()->branch_id)->get(),
            
            'programs'=>Product::where('category_id',1)->get(),
            'schools'=>School::where('id','!=',1)->get(),
            'branches'=>BranchResource::collection(Branch::where('school_id','!=',1)->get()),
            'statuses'=>ReferralStatus::get(),
            'paymentStatuses'=>PaymentStatus::get(),
            'users'=>User::where('branch_id',Auth::user()->branch_id)->get(),
            'can' => [
                'create' => Auth::user()->can('create', Referral::class),
                'import' => Auth::user()->can('import', Referral::class),
                'export' => Auth::user()->can('export', Referral::class),
                'delete' => Auth::user()->can('delete', Referral::class),
                'createForOthers'=>Auth::user()->can('createForOthers', Referral::class),
            ]
        ];
    }
}
