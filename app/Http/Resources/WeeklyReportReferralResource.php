<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Controllers\ApiResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserPermissionResource;
use Carbon\Carbon;

class WeeklyReportReferralResource extends JsonResource
{
   /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at->format('d/m/Y'),
            'prepared_at' => $this->prepared_at?Carbon::parse($this->prepared_at)->diffForHumans():null,
            'checked_at' => $this->checked_at?Carbon::parse($this->checked_at)->diffForHumans():null,
            'approved_at' => $this->approved_at?Carbon::parse($this->approved_at)->diffForHumans():null,
            'created_by' => $this->created_by,
            'preparer' => $this->preparer,
            'checker' => $this->checker,
            'approver' => $this->approver,
            'prepared_remark' => $this->prepared_remark,
            'checked_remark' => $this->checked_remark,
            'approved_remark' => $this->approved_remark,
            'branch_id' => $this->branch_id,
            'branch' => $this->branch,
            'is_kh' => $this->branch->is_kh,
            'from' => $this->from,
            'to' => $this->to,
            'referrals' => ReferralResource::collection($this->referrals),
        ];
    }
 
}
