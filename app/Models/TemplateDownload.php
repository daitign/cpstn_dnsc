<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateDownload extends Model
{
    protected $fillable = [
        'template_id',
        'user_id',
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

