<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramUser extends Model
{
    use HasFactory;
    public $table = 'program_user';
    public $fillable = [
        'user_id',
        'program_id'
    ];
}
