<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Template extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'process_id',
        'directory_id',
        'user_id',
        'folder_name',
        'template_id',
    ];

    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function process()
    {
        return $this->belongsTo(Process::class);
    }

    public function directory()
    {
        return $this->belongsTo(Directory::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function templateRemarks()
    {
        return $this->hasMany(TemplateRemark::class);
    }

    public function templateHistories()
    {
        return $this->hasMany(TemplateHistory::class);
    }

    public function templateDownloads()
    {
        return $this->hasMany(TemplateDownload::class);
    }
}
