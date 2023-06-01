<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['total_score'];

    public function score()
    {
        return $this->hasOne(SurveyArea::class);
    }

    public function getTotalScoreAttribute()
    {
        return (($this->score->promptness + $this->score->engagement + $this->score->cordiality) / 3);
    }
}
