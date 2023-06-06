<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditPlan extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    protected $dates = ['audit_plan_date'];
    
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
