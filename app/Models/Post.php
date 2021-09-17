<?php

namespace App\Models;

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
        'banned'
    ];

    
    
   
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
