<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyArea extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function area()
    {
        return $this->hasOne(Area::class);
    }
}
