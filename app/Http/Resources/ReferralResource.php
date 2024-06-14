<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class ReferralResource extends JsonResource
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
            'parent_referral_id' => $this->parent_referral_id,
            'student_name' => $this->student_name,
            'parent_name' => $this->parent_name,
            'parent2_name' => $this->parent2_name,
            'phone' => $this->phone,
            'phone2' => $this->phone2,
            'branch_id' => $this->branch_id,
            'branch' => new BranchResource($this->branch),
            'program' => $this->program?$this->program->name_en:null,
            'program_id' => $this->program_id,
            'level' => $this->level,
            'shift' => $this->shift,
            'start_date' => $this->start_date,
            'creator' => $this->creator->name,
            'creator_id' => $this->creator->id,
            'modifier' => $this->modifier->name,
            'referral_status_id' => $this->referral_status_id,
            'payment_status_id' => $this->payment_status_id,
            'fee' => $this->fee,
            'full_fee' => $this->full_fee,
            'discount' => $this->discount,
            'max_discount' => $this->max_discount,
            'discount_percentage' => $this->discount_percentage,
            'commission_percentage' => $this->commission_percentage,
            'commission' => $this->commission,
            'created_at' => $this->created_at->diffForHumans(),
            'paid_at' => $this->paid_at?Carbon::parse($this->paid_at)->diffForHumans():null,
            'attachments'=>$this->attachments,
            'is_riel' => $this->is_riel,
            'commission_type' => $this->commission_type,
            'wht' => $this->wht,
            'comment' => $this->weekly_report_comment,
            'enrolled_at' => $this->enrolled_at,
            'enrolled_by' => $this->enrolled_by,
            'can'=>[
                'update' => $this->payment_status_id!=2&&Auth::user()->can('update', Referral::class),
            ]
        ];
    }
}
