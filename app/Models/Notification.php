<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'action_url',
        'icon',
        'is_read',
        'data',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Static helper to dispatch notifications cleanly across the application.
     * Automatically sanitizes 4-byte emoji characters to prevent MySQL 1366 encoding errors on 3-byte utf8 tables.
     */
    public static function send($userId, string $type, string $title, string $message, ?string $actionUrl = null, string $icon = 'fa-bell', ?array $data = null)
    {
        if (!$userId) return null;

        // Strip 4-byte Unicode characters (emojis) for 3-byte MySQL utf8 compatibility
        $cleanTitle = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $title);
        $cleanMessage = preg_replace('/[\x{10000}-\x{10FFFF}]/u', '', $message);

        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => trim($cleanTitle),
            'message' => trim($cleanMessage),
            'action_url' => $actionUrl,
            'icon' => $icon,
            'is_read' => false,
            'data' => $data,
        ]);
    }
}
