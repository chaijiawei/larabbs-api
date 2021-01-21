<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function show()
    {
        $notifications = Auth::guard('api')->user()->notifications()->paginate();

        return NotificationResource::collection($notifications);
    }

    public function stats(Request $request)
    {
        return response()->json(['unread_count' => $request->user()->notify_count]);
    }

    public function markRead(Request $request)
    {
        $request->user()->markAsRead();

        return response(null, 204);
    }
}
