<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditReport extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    protected $with = ['consolidated_report'];

    public function consolidated_report()
    {
        return $this->hasOne(ConsolidatedAuditReport::class);
    }
}
