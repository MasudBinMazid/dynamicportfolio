<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    protected $fillable = [
        'session_id',
        'name',
        'email', 
        'message',
        'sender_type',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Scope for getting messages by session
    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    // Scope for unread messages
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // Scope for visitor messages
    public function scopeFromVisitor($query)
    {
        return $query->where('sender_type', 'visitor');
    }

    // Scope for admin messages
    public function scopeFromAdmin($query)
    {
        return $query->where('sender_type', 'admin');
    }
}
