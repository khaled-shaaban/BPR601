<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notifications\IndexRequest;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(IndexRequest $request)
    {
        $user = $request->user();
        $notifications = $user->notifications()->paginate($request->validated('per_page') ?? 15);

        return $this->respondWithPagination($notifications, [
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    public function unreadCount(Request $request)
    {
        return $this->respondWithData([
            'unread_count' => $request->user()->unreadNotifications()->count(),
        ]);
    }

    public function markAsRead(Request $request, string $id)
    {
        $request->user()->notifications()->findOrFail($id)->markAsRead();

        return $this->respondWithSuccess();
    }

    public function markAllAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();

        return $this->respondWithSuccess();
    }
}
