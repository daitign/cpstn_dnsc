<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['file_users', 'remarks', 'audit_report'];

    protected $appends = ['shared_users', 'trackings'];

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
        return $this->hasMany(FileRemark::class)->latest();
    }

    public function audit_report()
    {
        return $this->hasOne(AuditReport::class);
    }

    public function getTrackingsAttribute()
    {
        $track_records = [];
        if($this->type == 'evidences') {
            $track_records = ['dcc' => [], 'auditor' => []];

            $remarks = $this->remarks()->whereHas('user.role', function($q) {
                $q->where('role_name', 'Document Control Custodian');
            })->first();
            if($remarks) {
                $track_records['dcc'] = [
                    'color' => 'bg-success',
                    'user' => $remarks->user->firstname .' '.$remarks->user->lastname
                ];
            }

            $remarks = $this->remarks()->whereHas('user.role', function($q) {
                $q->whereIn('role_name', ['Internal Auditor', 'Internal Lead Auditor']);
            })->first();

            if($remarks) {
                $track_records['auditor'] = [
                    'color' => 'bg-danger',
                    'user' => $remarks->user->firstname .' '.$remarks->user->lastname
                ];
            }
        }
        return $track_records;
    }
}
