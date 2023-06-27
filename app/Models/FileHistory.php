<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileHistory extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    protected $appends = ['created_at_format'];

    public function getCreatedAtFormatAttribute()
    {
        return $this->created_at->format('M d, Y h:i A');
    }
}
