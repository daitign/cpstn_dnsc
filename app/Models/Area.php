<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Area extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'area_name',
        'area_description',
    ];

    public function institutes()
    {
        return $this->hasMany(Institute::class);
    }

    public function offices()
    {
        return $this->hasMany(Office::class);
    }
}
