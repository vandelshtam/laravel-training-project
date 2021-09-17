<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'comment',
        'post_id',
        'user_id',
        'commentable_type',
        'commentable_id',
        'banned',
    ];

    public function commentable()
    {
        return $this->morphTo();
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
