<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function survey()
    {
        return $this->hasMany(Survey::class);
    }

    public function averageRating()
    {
        $totals = [
            ['totals' => $this->survey->avg('promptness')],
            ['totals' => $this->survey->avg('engagement')],
            ['totals' => $this->survey->avg('cordiality')]
        ];
        return collect($totals)->avg('totals');
    }
}