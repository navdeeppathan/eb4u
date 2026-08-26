<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;
use Illuminate\Support\Facades\Artisan;

class ApiNotificationController extends Controller
{
    public function getNotifications(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['success' => false, 'count' => 0, 'notifications' => []], 401);
        }

        try {
            Artisan::call('ebike:check-rental-expirations');
        } catch (\Throwable $e) {}

        $unreadCount = $user->unreadNotificationsCount();
        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($n) {
                return [
                    'id' => $n->id,
                    'type' => $n->type,
                    'title' => $n->title,
                    'message' => $n->message,
                    'action_url' => $n->action_url,
                    'icon' => $n->icon,
                    'is_read' => (bool) $n->is_read,
                    'created_at_human' => $n->created_at ? $n->created_at->diffForHumans() : 'Just now',
                    'created_at' => $n->created_at ? $n->created_at->format('Y-m-d H:i:s') : null,
                ];
            });

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(Request $request, $id)
    {
        $user = $request->user();
        $notification = Notification::where('user_id', $user->id)->where('id', $id)->first();
        if ($notification) {
            $notification->update(['is_read' => true]);
        }

        return response()->json([
            'success' => true,
            'unread_count' => $user->unreadNotificationsCount(),
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $user = $request->user();
        Notification::where('user_id', $user->id)->where('is_read', false)->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'unread_count' => 0,
        ]);
    }
}
