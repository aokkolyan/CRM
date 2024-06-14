<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $fillable = [
        'id', 'name_kh', 'name_en', 'iso_3166_1', 'nationality_kh', 'nationlity_en', 'status'
    ];
}
