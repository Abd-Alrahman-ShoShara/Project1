<?php

namespace App\Http\Controllers;

use App\Models\BookingHotel;
use App\Models\BookingTicket;
use App\Models\BookingTripe;
use App\Models\RoomHotel;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;

class BookingTripeController extends Controller
{
    public function bookingTrip($trip_id) {
        $theTrip = Trip::find($trip_id);
        $user=User::find($theTrip->user_id);

        $bookingTicket = BookingTicket::where('trip_id', $trip_id)->first();
        $ticketPrice = $bookingTicket ? $bookingTicket->price : 0;

        $bookingHotels = BookingHotel::where('trip_id', $trip_id)->get();
        $hotelPrice = $bookingHotels->sum('price');
        $hotelPrice = $hotelPrice ?? 0;

        $totalPrice = $hotelPrice + $ticketPrice;

        if($user->wallet < $totalPrice){
            return response()->json([
                'message'=>"you don't have enough money",
            ], 422);
        }
        foreach ($bookingHotels as $bookingHotel) {
            $roomHotel = RoomHotel::find($bookingHotel->roomHotel_id);

            // Check if the RoomHotel exists
            if ($roomHotel) {
                $roomHotel->update([
                    'numberOfRoom' => $roomHotel->numberOfRoom - $bookingHotel->numberOfRoom,
                ]);
            }
        }

        if ($theTrip->state == 'UnderConstruction') {
            $alltrip = BookingTripe::create([
                'trip_id' => $trip_id,
                'price' => $totalPrice,
            ]);
            $theTrip->state = 'completed';
            $theTrip->save();

            $user->wallet -= $totalPrice;
            $user->points += $totalPrice*0.1;
            $user->save();

            NotificationController::sendNotification($totalPrice*0.1.'points has been added to your points',$user->id,$trip_id,'add-points');
            NotificationController::sendNotification($totalPrice.'$ has been deducted from your wallet',$user->id,$trip_id,'booking_private_trip');

        } else {
            return response()->json([
                'message' => 'The trip is already completed.',
            ], 422);
        }

        return response()->json([
            'message' => 'The trip was created successfully.',
            'trip' => $alltrip,
        ], 200);
    }
}
