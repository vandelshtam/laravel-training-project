<?php

namespace App\Models;

use App\Models\Chat;
use App\Models\Post;
use App\Models\Social;
use App\Models\Message;
use App\Models\Userlist;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'info_id',
        'social_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'name'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
    public function infos()
    {
        return $this->hasMany(Info::class);
    }
    public function socials()
    {
        return $this->hasMany(Social::class);
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
    
    
    public function userlists()
    {
        return $this->morphMany(Userlist::class, 'userlistable');
    }
    public function messages()
    {
        return $this->morphMany(Message::class, 'messageable');
    }

    public function userlist()
    {
        return $this->morphOne(Userlist::class, 'userlistable');
    }
    public function info()
    {
        return $this->belongsTo(Info::class);
    }
    public function social()
    {
        return $this->belongsTo(Social::class);
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
