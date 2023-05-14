<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Area extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $guarded = [];
    
    public function parent()
    {
        return $this->belongsTo(Area::class, 'parent_area');
    }

    public function children()
    {
        return $this->hasMany(Area::class, 'parent_area');
    }

    public function scopeOffices(Builder $query): void
    {
        $query->where('type', 'office');
    }

    public function scopeInstitutes(Builder $query): void
    {
        $query->where('type', 'institute');
    }

    public function scopeProcess(Builder $query): void
    {
        $query->where('type', 'process');
    }

    public function scopeProgram(Builder $query): void
    {
        $query->where('type', 'program');
    }

    public function survey()
    {
        return $this->hasMany(SurveyArea::class);
    }
}
