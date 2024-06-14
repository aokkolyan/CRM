<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WeeklyReportReferral extends Model
{
    use HasFactory,SoftDeletes;
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }
    protected $fillable = [
        'id', 'prepared_by', 'checked_by', 'approved_by', 'created_by','branch_id','remark',
        'prepared_at', 'checked_at', 'approved_at','prepared_remark','checked_remark','approved_remark',
        'from','to'

    ];
    public function referrals()
    {
        return $this->hasMany(Referral::class);
    } 
    public function preparer()  {
        return $this->belongsTo(User::class,'prepared_by');
    }
    public function checker()  {
        return $this->belongsTo(User::class,'checked_by');
    }
    public function approver()  {
        return $this->belongsTo(User::class,'approved_by');
    }
    public function branch()  {
        return $this->belongsTo(Branch::class,'branch_id');
    }
   
   
   
}
