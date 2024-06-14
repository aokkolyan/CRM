<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attachment extends Model
{
    use HasFactory;  
    use SoftDeletes;
    protected $fillable = [
        'id','module_id','ref_id','path','name'
    ];
}
