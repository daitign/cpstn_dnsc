<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends Model
{
    use HasFactory, SoftDeletes;

    
    protected $fillable = [
        'program_name',
        'program_description',
        'institute_id',
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function processes()
    {
        return $this->hasMany(Process::class);
    }
}
