<?php

namespace App\Models;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Repositories\DirectoryRepository;

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

    public function getAreaFullName()
    {
        $dr = new DirectoryRepository;
        $parents = $dr->getAreaTree($this);
        $parents = Arr::pluck($parents, 'area_name');
        $parents[] = $this->name;
        
       return implode(' > ', $parents);
    }

    public function audit_plan_area()
    {
        return $this->hasMany(AuditPlanArea::class);
    }
}
