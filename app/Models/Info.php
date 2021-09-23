<?php

namespace App\Models;


use App\Models\Post;
use App\Models\User;
use App\Models\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Info extends Model
{
    use HasFactory;

    protected $fillable = [
        'occupation',
        'location',
        'position',
        'phone',
        'status',
        'avatar',
        'user_id',
        'infosable_id',
    ];

    public function infosable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function messages()
    {
        return $this->morphMany(Message::class, 'messageable');
    }

    public function userlists()
    {
        return $this->morphMany(Userlist::class, 'userlistable');
    }
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
