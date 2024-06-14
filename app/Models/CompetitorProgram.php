<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetitorProgram extends Model
{
    use HasFactory;
    protected $fillable = ['promotion','program_price','program_student','program_id','map_location_id'];

    public function program()
    {
        $this->belongsTo(Program::class);
    }
}
