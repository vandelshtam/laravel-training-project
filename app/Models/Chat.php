<?php

namespace App\Models;

use App\Models\Post;
use App\Models\User;
use App\Models\Message;
use App\Models\Userlist;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_chat',
        'author_user_id',
        'banned',
        'chat_avatar',
        'status_chat',
        'location',
        'favorites',
        'name',
        'chat_id',
        'role',
        'user_id',
    ];

    public function messages()
    {
        return $this->morphMany(Message::class, 'messageable');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function userlist()
    {
        return $this->morphOne(Userlist::class, 'userlistable');
    }
    public function userlists()
    {
        return $this->morphMany(Userlist::class, 'userlistable');
    }
    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
