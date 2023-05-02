<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateHistory extends Model
{
    protected $fillable = [
        'template_id',
        'location',
        'filename',
        'original_name',
        'extension',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}
