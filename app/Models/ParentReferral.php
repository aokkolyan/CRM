<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ParentReferral extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'id', 'parent_name', 'parent2_name', 'phone', 'phone2',
    ];
    public function referrals()
    {
        return $this->hasMany(Referral::class);
    } 
    public function processes()
    {
        return $this->hasMany(ReferralProcess::class);
    } 
}
