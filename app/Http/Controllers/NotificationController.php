<?php

namespace App\Http\Controllers;

use App\Events\NotificationSent;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    

    public static function sendNotification($user_id,$notification)
    {
        // Validate the request data
        // $validatedData = $request->validate([
        //     'user_id' => 'required|exists:users,id',
        //     'message' => 'required|string',
        // ]);

        // Find the user
        // $user = User::findOrFail($validatedData['user_id']);
        

        // Create the notification
        $user=User::find($user_id);
        $notification = Notification::create([
            'user_id' => $user_id,
            'body' => $notification,
        ]);

        // Broadcast the notification event
        event(new NotificationSent($user, $notification));

        // Return a response
        return response()->json([
            'message' => 'Notification sent successfully',
        ], 200);
    }
}
