<?php

namespace App\Models;

use App\Models\Info;
use App\Models\User;
use App\Models\Social;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Post extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'text',
        'avatar_post',
        'name_post',
        'title_post',
        'favorites',
        'postable_id',
        'banned',
        'info_id',
        'social_id',
        'post_id',
    ];

    
    
   
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    public function info()
    {
        return $this->belongsTo(Info::class);
    }
    public function social()
    {
        return $this->belongsTo(Social::class);
    }
}
