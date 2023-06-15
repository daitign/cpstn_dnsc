<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditPlan extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    protected $dates = ['audit_plan_date'];
    
    public function areas()
    {
        return $this->hasManyThrough(
            Area::class,
            AuditPlanArea::class,
            'audit_plan_id',
            'id',
            'id',
            'area_id'
        );
    }

    public function users()
    {
        return $this->hasMany(AuditPlanUser::class);
    }
}
