<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Incident extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'severity',
        'status',
        'assigned_to',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(IncidentComment::class);
    }

    public function isAssigned(): bool
    {
        return !is_null($this->assigned_to);
    }

    public function canBeUpdatedBy(User $user): bool
    {
        return $user->role === 'admin' || $user->id === $this->user_id;
    }

    public function canBeCommentedBy(User $user): bool
    {
        return $user->role === 'admin' || 
               $user->id === $this->user_id || 
               $user->id === $this->assigned_to;
    }
}
