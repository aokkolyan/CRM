<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Referral extends Model
{
    use HasFactory,SoftDeletes;
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
    protected $fillable = [
        'id', 'parent_name', 'parent2_name', 'phone', 'phone2', 'branch_id',
        'student_name','level','start_date','referral_status_id','payment_status_id',
        'payment','commission_percentage','commission','discount_percentage','discount',
        'created_by','updated_by','program_id','full_fee','fee','shift','paid_at','is_riel',
        'parent_referral_id','weekly_report_referral_id','commission_payment_id',
        'commission_type','wht','max_discount','weekly_report_comment','enrolled_at','enrolled_by'
    ];
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    } 
    public function creator()
    {
        return $this->belongsTo(User::class,'created_by');
    } 
    public function modifier()
    {
        return $this->belongsTo(User::class,'updated_by');
    } 
    public function processes()
    {
        return $this->hasMany(ReferralProcess::class);
    } 
    public function attachments()
    {
        return $this->hasMany(Attachment::class,'ref_id');
    } 
    public function program()
    {
        return $this->belongsTo(Product::class,'program_id');
    }
    public function parent()
    {
        return $this->belongsTo(ParentReferral::class,'parent_referral_id');
    } 
    public function report()
    {
        return $this->belongsTo(WeeklyReportReferral::class,'weekly_report_referral_id');
    } 
    // public function getCommissionAttribute($value)
    // {
    //     return number_format($value, 2); // Convert to 2 decimal places
    // }
  
}
