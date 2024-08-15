<?php

namespace App\Http\Controllers;

use App\Events\NotificationSent;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{


    public static function sendNotification($message,$user_id,$event)
    {

        // Validate the request data
        // $validatedData = $request->validate([
        //     'user_id' => 'required|exists:users,id',
        //     'message' => 'required|string',
        // ]);

        // Find the user
        // $user = User::findOrFail($validatedData['user_id']);


        // Create the notification
        // $user=User::find($user_id);
        $notification = Notification::create([
            'user_id' => $user_id,
            'body' => $message,
        ]);

        // Broadcast the notification event
        event(new NotificationSent($message,$user_id,$event));
    }

    public function getAllNotifications(){

        $AllNotifications=Notification::where('user_id',Auth::user()->id)->get();
        if(!$AllNotifications){
            return response()->json([
                'message'=>'there is no a new notifications',
            ]);
        }else{
            return response()->json([
                'theNotifcations'=>$AllNotifications,
            ]);
        }
    }

}
