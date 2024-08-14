<?php

namespace App\Http\Controllers;

use App\Events\NotificationSent;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{


    public static function sendNotification()
    {
       // echo('djkbsdjgk');
       // $user_id,$notification
        $SERVER_API_KEY = 'AAAAkpsZvbk:APA91bECz65n5ffneeOXfcsF_2401jzqEhdJHUCPtl-P1RnRWbha6pexzKckIrNfaJ7SlV5-sAff2pQPkUC84BiiiqTYdoEcnqoiS1VobYJqY9Ezl8SIr97iMwx2TT6N8LxlibXZSfgt';

        $token_1 ='ftt7h5H6R7enARQEYSnF1V:APA91bGEsmx1EAnYc9DXU4c6qHRbnQY9eqOiNZfrrOwfafH4q6Um86uIn04C1kQYieJCfHrsiZBCwNzXR8osKhs8CLXSVe5fd6pPiuCtzqii7LnYbKg3w8EmIsa0rTv5Qnis-t1X95kY';

        $data = [

            "registration_ids" => [
                $token_1
            ],

            "notification" => [

                "title" => 'noti',

                "body" =>['Hiiiiiiiiiiiiiiiiiii'],

                "sound"=> "default" // required for sound on ios

            ],

        ];

        $dataString = json_encode($data);

        $headers = [

            'Authorization: key=' . $SERVER_API_KEY,

            'Content-Type: application/json',

        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);

        $response = curl_exec($ch);

        dd($response);
        // Validate the request data
        // $validatedData = $request->validate([
        //     'user_id' => 'required|exists:users,id',
        //     'message' => 'required|string',
        // ]);

        // Find the user
        // $user = User::findOrFail($validatedData['user_id']);


        // Create the notification
        // $user=User::find($user_id);
        // $notification = Notification::create([
        //     'user_id' => $user_id,
        //     'body' => $notification,
        // ]);

        // // Broadcast the notification event
        // event(new NotificationSent($user, $notification));

        // // Return a response
        // return response()->json([
        //     'message' => 'Notification sent successfully',
        // ], 200);
    }
}
