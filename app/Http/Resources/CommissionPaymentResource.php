<?php

namespace App\Http\Resources;


use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CommissionPaymentResource extends JsonResource
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
            'referrer' => $this->referrer,
            'payer' => $this->payer,
            'total_payment' => $this->total_payment,
            'attachments' => $this->attachments,
            'paid_at'=> $this->paid_at,
        ];
    }
}
