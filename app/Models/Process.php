<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Process extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'process_name',
        'process_description',
        'program_id',
        'office_id'
    ];

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }
}
