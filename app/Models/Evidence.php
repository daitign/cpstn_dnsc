<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Evidence extends Model
{
    use HasFactory,SoftDeletes;

    public $fillable = [
        'directory_id',
        'user_id',
        'folder_name',
        'evidence_id',
        'process_id'
    ];

    public function directory()
    {
        return $this->belongsTo(Directory::class);
    }

    public function evidence_histories()
    {
        return $this->hasMany(EvidenceHistory::class);
    }

    public function evidence_remarks()
    {
        return $this->hasMany(EvidenceRemark::class);
    }

    public function evidence_downloads()
    {
        return $this->hasMany(EvidenceDownload::class,'evidence_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function process()
    {
        return $this->belongsTo(Process::class);
    }
}
