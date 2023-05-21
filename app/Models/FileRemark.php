<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileRemark extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    protected $with = ['user'];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'created_at_formatted',
        'created_at_for_humans'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('M d, Y h:i A');
    }

    public function getCreatedAtForHumansAttribute()
    {
        return $this->created_at->diffForHumans();
    }
}
