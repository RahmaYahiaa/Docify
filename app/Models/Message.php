<?php

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Message extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia;
    protected $fillable = [
        'conversation_id',
        'sender_id',
        'body',
        'type',
        'media_id',
        'attachment_url',
        'attachment_type',
        'attachment_name',
        'attachment_size',
        'voice_duration',
        'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'attachment_size' => 'integer',
        'voice_duration' => 'integer',
    ];

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
