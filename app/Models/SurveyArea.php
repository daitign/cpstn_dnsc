<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyArea extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $appends = ['total'];

    public function area()
    {
        return $this->hasOne(Area::class, 'id', 'area_id');
    }

    public function getTotalAttribute()
    {
        return (($this->promptness + $this->engagement + $this->cordiality) / 3);
    }
}
