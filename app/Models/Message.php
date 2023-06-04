<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['sender', 'created_at_formatted'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getSenderAttribute()
    {
        return sprintf('%s %s (%s)', $this->user->firstname, $this->user->surname, $this->user->role->role_name);
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('M d, Y h:i A');
    }
}
