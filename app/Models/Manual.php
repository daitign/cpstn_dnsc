<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manual extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'process_id',
        'directory_id',
        'user_id',
        'name',
        'description',
    ];

}
