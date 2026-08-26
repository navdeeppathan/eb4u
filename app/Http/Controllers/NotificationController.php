<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;

class NotificationController extends Controller
{
    /**
     * Real-Time Notification Polling Endpoint.
     * Returns unread count and latest unread / recent notifications for current authenticated user.
     */
    public function getNotifications()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'count' => 0,
                'notifications' => []
            ]);
        }

        // Run rental expiration check on the fly for logged in user so alerts trigger instantly
        try {
            Artisan::call('ebike:check-rental-expirations');
        } catch (\Throwable $e) {
            // Silence if artisan command fails on missing DB table until user executes SQL
        }

        try {
            $unreadCount = $user->unreadNotificationsCount();
            $notifications = Notification::where('user_id', $user->id)
                ->latest()
                ->take(15)
                ->get()
                ->map(function ($n) {
                    return [
                        'id' => $n->id,
                        'type' => $n->type,
                        'title' => $n->title,
                        'message' => $n->message,
                        'action_url' => $n->action_url,
                        'icon' => $n->icon,
                        'is_read' => $n->is_read,
                        'created_at_human' => $n->created_at ? $n->created_at->diffForHumans() : 'Just now',
                    ];
                });

            return response()->json([
                'success' => true,
                'count' => $unreadCount,
                'notifications' => $notifications
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'count' => 0,
                'notifications' => [],
                'error' => 'Database table notifications does not exist yet. Please execute schema.sql.'
            ]);
        }
    }

    /**
     * Mark a single notification as read.
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false], 401);
        }

        $notification = Notification::where('user_id', $user->id)->where('id', $id)->first();
        if ($notification) {
            $notification->update(['is_read' => true]);
        }

        return response()->json([
            'success' => true,
            'count' => $user->unreadNotificationsCount()
        ]);
    }

    /**
     * Mark all notifications as read for current user.
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false], 401);
        }

        Notification::where('user_id', $user->id)->where('is_read', false)->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'count' => 0
        ]);
    }
}
