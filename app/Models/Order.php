<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'provider_id',
        'service_id',
        'status',
        'total_amount',
        'requirements',
        'scheduled_date',
        'scheduled_time',
        'notes',
        'accepted_at',
        'completed_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'scheduled_date' => 'date',
        'scheduled_time' => 'datetime:H:i',
        'accepted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeForClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeForProvider($query, $providerId)
    {
        return $query->where('provider_id', $providerId);
    }

    // Helper methods
    public function canBeAccepted()
    {
        return $this->status === 'pending';
    }

    public function canBeCompleted()
    {
        return in_array($this->status, ['accepted', 'in_progress']);
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'accepted']);
    }
}
