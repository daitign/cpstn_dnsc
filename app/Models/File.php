<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['file_users', 'remarks', 'audit_report'];

    protected $appends = ['shared_users'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function file_users()
    {
        return $this->hasMany(FileUser::class);
    }

    public function getSharedUsersAttribute()
    {
        return implode(', ', $this->file_users->pluck('user_id')->toArray() ?? []);
    }

    public function remarks()
    {
        return $this->hasMany(FileRemark::class);
    }

    public function audit_report()
    {
        return $this->hasOne(AuditReport::class);
    }
}
