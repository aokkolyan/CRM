<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Status extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'id', 'name_en', 'name_kh', 'code', 'description', 'created_at', 'updated_at','created_by','deleted_by','color',
    ];
    public function stage()
    {
        return $this->hasMany(Stage::class);
    }

}
