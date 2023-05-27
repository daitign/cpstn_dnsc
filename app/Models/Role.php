<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $appends = ['directories'];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function getDirectoriesAttribute()
    {
        $directories = ['Manuals', 'Templates'];
        if($this->role_name == 'Staff') {
            $directories = ['Manuals', 'Templates'];
        }elseif(in_array($this->role_name, ['Process Owner', 'Document Control Custodian'])) {
            $directories = ['Templates', 'Manuals', 'Evidences'];
        }elseif(in_array($this->role_name, ['Internal Auditor', 'Internal Lead Auditor'])) {
            $directories = ['Templates', 'Audit Reports', 'Evidences', 'Template'];
        }elseif($this->role_name == 'Quality Assurance Director') {
            $directories = ['Templates', 'Manuals', 'Audit Reports', 'Survey Reports'];
        }elseif($this->role_name == 'Human Resources') {
            $directories = ['Templates', 'Survey Reports'];
        }elseif($this->role_name == 'College Management Team') {
            $directories = ['Templates', 'Consolidated Audit Reports', 'Survey Reports'];
        }

        return $directories;
    }
}
