<?php

namespace App\Models;

use App\Models\Info;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'chat_id',
        'user_id',
        'messageable_type',
        'messageable_id',
        'create_at',
        'updated_at',
        'info_id',
    ];

    public function messageable()
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function info()
    {
        return $this->belongsTo(Info::class);
    }
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}
