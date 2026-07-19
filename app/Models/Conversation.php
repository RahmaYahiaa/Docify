<?php

namespace App\Models;

use App\Enums\Chat\ConversationStatusEnum;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;
    protected $fillable = [
        'type',
        'status',
        'last_message_at',
    ];
    protected $casts = [
        'last_message_at' => 'datetime',
        'status' => ConversationStatusEnum::class,
    ];
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'conversation_user')
            ->withPivot('joined_at', 'unread_count');
    }
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany('created_at');
    }

    /**
     * Only conversations that have at least one message sent.
     * Pending conversations (no messages yet) are hidden from lists.
     */
    public function scopeActive($query)
    {
        return $query->where('status', ConversationStatusEnum::ACTIVE);
    }
    public function isPending(): bool
    {
        return $this->status === ConversationStatusEnum::PENDING;
    }

    public function activate(): void
    {
        if ($this->isPending()) {
            $this->updateQuietly(['status' => ConversationStatusEnum::ACTIVE]);
        }
    }
}
