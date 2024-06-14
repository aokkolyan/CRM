<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected static $logAttributes = [
        'from_user_id',
        'user_id',
        'message',
        'title',
        'link',
        'role_id',
        'status',
    ];
    protected $fillable = [
        'from_user_id',
        'user_id',
        'message',
        'title',
        'link',
        'role_id',
        'status',
    ];
}
