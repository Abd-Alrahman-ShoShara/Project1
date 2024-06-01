<?php

namespace App\Http\Controllers;

use App\Models\TripPoint;
use App\Models\UserPublicTrip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPublicTripController extends Controller
{
    public function bookingPublicTrip(Request $request){
        $request->validate([
            'tripPoint_id'=>'required|integer',
            'numberOfTickets'=>'required|integer',
            'VIP'=>'required|boolean',
        ]);

        $tripPoint=TripPoint::find($request->tripPoint_id);
        $tripPointPrice=$tripPoint->price;
        
        $totalPrice= $request->numberOfTickets * $tripPointPrice ;

        if($request->VIP){
            $totalPrice += 0.3 * $totalPrice;
        }

        
        $PointBooking =UserPublicTrip::create([
            'user_id'=>Auth::user()->id,
            'tripPoint_id'=>$request->tripPoint_id,
            'numberOfTickets'=>$request->numberOfTickets,
            'price'=>$totalPrice,
        ]); 

        if($request->numberOfTickets>$tripPoint->numberOfTickets){
            return response([
                'the number of ticket you can book:' => $tripPoint->numberOfTickets,
            ], 422);
        }
        TripPoint::where('id',$request->tripPoint_id)
        ->update(['numberOfTickets'=>$tripPoint->numberOfTickets-$request->numberOfTickets]);


        return response([
            'message' => 'booking successfully.',
            'theBooking:'=>$PointBooking,
        ], 200);
        
    }
}
