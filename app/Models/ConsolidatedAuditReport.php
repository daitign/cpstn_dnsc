<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsolidatedAuditReport extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    protected $with = ['file'];

    public function file()
    {
        return $this->belongsTo(File::class);
    }
}
