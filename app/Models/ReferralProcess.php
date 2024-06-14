<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class ReferralProcess extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'id','referral_status_id','remark','created_by','referral_id','parent_referral_id'
    ];
    public function creator()
    {
        return $this->belongsTo(User::class,'created_by');
    } 
}
