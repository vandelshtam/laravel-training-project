<?php

namespace App\Models;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    /*
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    */
    public function infos()
    {
        return $this->belongsToMany(Info::class);
    }
    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
