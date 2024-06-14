<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class School extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'id', 'name_en', 'name_kh', 'code', 'description', 'created_at', 'updated_at','created_by','deleted_by','is_riel' ,'point_multiplyier','coupon_percentage'
    ];

    public function branch()
    {
        return $this->hasMany(Branch::class);
    }
    public function commission_types()
    {
        return $this->hasMany(SchoolUserTypeCommissionType::class);
    }
}
