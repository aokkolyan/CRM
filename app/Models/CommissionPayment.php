<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommissionPayment extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'id', 'for_user', 'paid_by', 'paid_at', 'remark','total_payment'
    ];
    public function referrals()
    {
        return $this->hasMany(Referral::class);
    } 
    public function attachments()
    {
        return $this->hasMany(Attachment::class,'ref_id')->where('module_id',16);
    } 
    public function payer()
    {
        return $this->belongsTo(User::class,'paid_by');
    } 
    public function referrer()
    {
        return $this->belongsTo(User::class,'for_user');
    } 
}
