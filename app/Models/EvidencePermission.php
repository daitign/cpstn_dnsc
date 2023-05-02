<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvidencePermission extends Model
{
    use HasFactory;

    public $fillable = [
        'evidence_id',
        'program_id'
    ];
}
