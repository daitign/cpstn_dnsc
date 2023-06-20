<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['total_score'];

    public function getTotalScoreAttribute()
    {
        return (($this->promptness + $this->engagement + $this->cordiality) / 3);
    }

    public function facility()
    {
        return $this->hasOne(Facility::class, 'id', 'facility_id');
    }
}
