<?php

namespace App\Http\Controllers;

use App\Events\NotificationSent;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{


    public static function sendNotification($message,$user_id,$trip_id,$event)
    {
        $found=Notification::where(['user_id' => $user_id,
            'body' => $message,
            'event' => $event,
            'trip_id' => $trip_id,])->first();

            if(!$found){
        $notification = Notification::create([
            'user_id' => $user_id,
            'body' => $message,
            'event' => $event,
            'trip_id' => $trip_id,

        ]);

        // Broadcast the notification event
        event(new NotificationSent($message,$user_id,$trip_id,$event));
        }
        
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
