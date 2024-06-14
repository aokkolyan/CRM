<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MapLocation extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'name',
        'name_branch_manager',
        'cultural_level',
        'price_program',
        'specail_program',
        'address',
        'qaunity_staff',
        'quantity_student',
        'social_media',
        'other',
        'icon',
        'branch_id',
        'lat',
        'long',
        
        'is_competitor',
    ];
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function mapIcon()
    {
        return $this->hasOne(Attachment::class,'id','icon');
    }
    public function programs()
    {
        return $this->hasMany(CompetitorProgram::class,'map_location_id');
    }
}
