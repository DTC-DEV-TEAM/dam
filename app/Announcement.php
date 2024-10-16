<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'announcements';

    protected $fillable = [
        'title',
        'message',
        'status',
        'created_at',
        'created_by',
        'updated_by',
        'updated_at',
    ];

    public function users()
    {
        return $this->belongsToMany(Users::class,'announcement_user')->withTimestamps();
    }
}
