<?php

namespace App\Models;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'post_id',
        'user_id',
        'imageable_id',
        'imageable_type',
    ];

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function imageable()
    {
        return $this->morphTo();
    }
}
