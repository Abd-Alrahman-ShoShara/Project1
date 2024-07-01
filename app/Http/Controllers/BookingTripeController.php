<?php

namespace App\Http\Controllers;

use App\Models\BookingHotel;
use App\Models\BookingTicket;
use App\Models\BookingTripe;
use App\Models\RoomHotel;
use App\Models\Trip;
use Illuminate\Http\Request;

class BookingTripeController extends Controller
{
    public function bookingTrip($trip_id){

        $bookingTicket=BookingTicket::where('trip_id',$trip_id)->first();
        $ticketPrice=$bookingTicket->price;

        $bookingHotels=BookingHotel::where('trip_id',$trip_id)->get();        
        $hotelPrice = $bookingHotels->sum('price');     
        $totalPrice = $hotelPrice + $ticketPrice ;   
        
        foreach ($bookingHotels as $bookingHotel) {
            $roomHotel = RoomHotel::find($bookingHotel->roomHotel_id);
    
            // Check if the RoomHotel exists
            if ($roomHotel) {
                $roomHotel->update([
                    'numberOfRoom' => $roomHotel->numberOfRoom - $bookingHotel->numberOfRoom, 
                ]);
            }
        }
        $theTrip=Trip::find($trip_id);
        if($theTrip->state=='UnderConstruction'){
            $alltrip = BookingTripe::create([
                'trip_id'=>$trip_id,
                'price'=>$totalPrice,
            ]);
            $theTrip->state='completed';
            $theTrip->save();
        }else{
            return response()->json([
                'message'=>'the plane is created already',
            ],422);
        }
            
        return response()->json([
            'message'=>'the plane created successfuly',
            'plane'=>$alltrip,
        ],200);
    }
}
