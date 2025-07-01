<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'sender_id',
        'receiver_id',
        'message',
        'is_read',
        'message_type',
        'file_path',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    public function scopeBetweenUsers($query, $user1Id, $user2Id)
    {
        return $query->where(function ($q) use ($user1Id, $user2Id) {
            $q->where('sender_id', $user1Id)->where('receiver_id', $user2Id)
              ->orWhere('sender_id', $user2Id)->where('receiver_id', $user1Id);
        });
    }

    // Helper methods
    public function markAsRead(): void
    {
        $this->update(['is_read' => true]);
    }

    public function isFromUser($userId): bool
    {
        return $this->sender_id === $userId;
    }

    public function isToUser($userId): bool
    {
        return $this->receiver_id === $userId;
    }

    public function getFormattedTimeAttribute(): string
    {
        return $this->created_at->format('M d, Y g:i A');
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    // Check if message has attachment
    public function hasAttachment(): bool
    {
        return !empty($this->file_path);
    }

    // Get file extension
    public function getFileExtensionAttribute(): ?string
    {
        if (!$this->hasAttachment()) {
            return null;
        }
        return pathinfo($this->file_path, PATHINFO_EXTENSION);
    }

    // Check if file is image
    public function isImage(): bool
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        return in_array($this->file_extension, $imageExtensions);
    }
}
