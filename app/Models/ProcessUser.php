<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessUser extends Model
{
    use HasFactory;
    public $table = 'process_user';
    public $fillable = [
        'user_id',
        'process_id'
    ];
}
