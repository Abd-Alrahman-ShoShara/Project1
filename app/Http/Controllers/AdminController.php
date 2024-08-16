<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\BookingTicket;
use App\Models\BookingTripe;
use App\Models\NormalUser;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class AdminController extends Controller
{
    //
    public function login(Request $request)
    {
        $request->validate([
            'phone'=>'required|numeric|digits:10',
            'password'=>'required|min:6',
        ]);

        $normalUser=NormalUser::where('phone',$request->phone)->first();
        if(!$normalUser){
            return response([
                'message'=>'the phone is wrong',
            ],422);
        }

        $user_id=$normalUser->user_id;
        $user = User::findOrFail($user_id);

        if(!$normalUser|| !Hash::check($request->password,$normalUser->password)){
            return response([
                'message'=>'The provided credentials are incorrect'
            ],422);
        }
        if($normalUser->role=='admin'){
            $token=$user->createToken('auth_token')->accessToken;
                return response([
                    'token'=>$token
                ],200);
        }
        return response([
            'message'=>'no access',
        ],422);

    }

    // Update admin credentials function
    public function updateAdmin(Request $request)
    {
        $attr=$request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
        ]);

        $userAdmin = Auth::user()->update([
            'name'=>$request->name,
        ]);
        $normalAdmin=NormalUser::where('user_id',Auth::user()->id)->update(['phone'=>$request->phone,]);

        if($userAdmin && $normalAdmin){
            return response()->json([
                'message'=>'updated successfully',
            ]);
        }
        return response()->json([
            'message'=>'something wronge',
        ]);


    }
    public function updateAdminPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6',
            'NewPassword'=>'required|min:6|confirmed',
        ]);
        $user_id=auth()->user()->id;
        $normalUser = NormalUser::where('user_id',$user_id)->first();


        if(Hash::check($request->password,$normalUser->password) ){
            $normalUser->update(['password' => Hash::make($request['NewPassword'])]);
            return response()->json([
                'message'=> 'the password is updated',
                ],200);
        }
        return response()->json([
        'message'=> 'the old password is wrong',
        ],422);

    }

    public function logoutAdmin(){
        User::find(Auth::id())->tokens()->delete();
        return response([
            'message'=>'Logged out sucesfully'
        ],200);
    }

    public function adminInfo(){
        return response()->json([
        'theAdmin:'=>NormalUser::where('role','admin')->select('user_id','phone')->with('user:id,name')->get(),
        ]);
    }

    public function addToWallet(Request $request)
    {
        // Define custom validation rules for 'EmailOrPhone'
    Validator::extend('email_or_phone', function ($attribute, $value, $parameters, $validator) {
        // Check if the value is a valid email or a valid phone number
        return filter_var($value, FILTER_VALIDATE_EMAIL) || preg_match('/^[0-9]{10,15}$/', $value);
    });
    $attr = $request->validate([
        'EmailOrPhone' => 'required|email_or_phone',
        'amount' => 'required|numeric|min:0'
    ], [
        'email_or_phone' => 'The :attribute must be a valid email address or phone number.'
    ]);

        $user = User::whereHas('googleUser', function ($query) use ($attr) {
            $query->where('email', $attr['EmailOrPhone']);
        })->orWhereHas('normalUser', function ($query) use ($attr) {
            $query->where('phone', $attr['EmailOrPhone']);
        })->first();

        if(!$user){
        return response()->json([
            'message'=>'email or phone not found...'
        ]);}

        $user->wallet=$attr['amount'];
        $user->save();

        NotificationController::sendNotification($attr['amount'].'$ has been added to your wallet',$user->id,null,'add_to_wallet');

        return response()->json([
            'message'=>'amount added successful',
            'amount'=>$attr['amount']
        ]);

    }

    public function distroyedAirport(Request $request, $airport_id) {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after:from'
        ]);
    
        $from = Carbon::parse($request->from);
        $to = Carbon::parse($request->to);
    
        $bookingTickets = BookingTicket::whereHas('ticket', function ($query) use ($airport_id) {
            $query->where('airport_id1', $airport_id)->orWhere('airport_id2', $airport_id);
        })->get();
    
        $cancelledTrips = []; // Track cancelled trips
    
        foreach ($bookingTickets as $bookingTicket) {
            $trip = Trip::where([
                ['state', 'completed'],
                ['id', $bookingTicket->trip_id]
            ])
            ->whereBetween('dateOfTrip', [$from, $to])
            ->first();
    
            if ($trip) {
                DB::transaction(function () use ($trip, $bookingTicket, &$cancelledTrips) {
                    $user = User::find($trip->user_id);
                    $bookingTrip = BookingTripe::where('trip_id', $trip->id)->first();
    
                    if ($bookingTrip) {
                        $user->wallet += $bookingTrip->price;
                        $user->points += $bookingTrip->price * 0.2;
                        $user->save();
    
                        $trip->state = 'cancelled';
                        $trip->save();
    
                        $notificationMessage = $bookingTrip->price . '$ has been added to your wallet. Due to a glitch at the airport, your trip has been cancelled. You have been compensated with ' . $bookingTrip->price * 0.2 . ' points as an expression of our regret.';
    
                        NotificationController::sendNotification($notificationMessage, $user->id, $trip->id, 'private-distroyedAirport');
    
                        // Add to cancelled trips array
                        $cancelledTrips[] = $trip->id;
                    }
                });
            } else {
                // No trip found, continue to the next booking
            }
        }
    
        if (count($cancelledTrips) > 0) {
            return response()->json([
                'message' => 'The airport has been stopped. The following trips have been cancelled: ' . implode(', ', $cancelledTrips) 
            ]);
        } else {
            return response()->json([
                'message' => 'There are no trips in this date range.'
            ]);
        }
    }
    
}
