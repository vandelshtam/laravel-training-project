<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'info_id',
        'status_chat',
        'favorites',
        'name',
        'chat_id',
        'role',
        'user_id',
    ];

    public function userlistable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    public function infos()
    {
        return $this->belongsToMany(Info::class);
    }
}
