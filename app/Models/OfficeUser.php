<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeUser extends Model
{
    use HasFactory;

    public $table = 'office_user';
    public $fillable = [
        'user_id',
        'office_id'
    ];
}
