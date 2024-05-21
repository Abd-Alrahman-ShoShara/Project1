<?php

namespace App\Http\Controllers;

use App\Models\BookingHotel;
use App\Models\BookingTicket;
use App\Models\Trip;
use App\Models\TripDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    public function createTrip(Request $request)
    {

        $attr = $request->validate([
            'from' => 'required|string|max:255',
            'to' => 'required|string|max:255',
            'dateOfTrip' => 'required|date|after_or_equal:today',
            'dateEndOfTrip' => 'required|date|after:dateOfTrip', 
            'numOfPersons' => 'required|integer|min:1',
        ]);

        $trip = Trip::create([
            'user_id' => Auth::user()->id,
            'from' => $attr['from'],
            'to' => $attr['to'],
            'dateOfTrip' => $attr['dateOfTrip'],
            'dateEndOfTrip' => $attr['dateEndOfTrip'],
            'numOfPersons' => $attr['numOfPersons'],
        ]);
    

        $currentDate = new \DateTime($attr['dateOfTrip']);
        $endDate = new \DateTime($attr['dateEndOfTrip']);
    
        // Loop through each day and create a TripDay entry
        while ($currentDate < $endDate) {
            TripDay::create([
                'trip_id' => $trip->id,
                'date' => $currentDate->format('Y-m-d'),
            ]);
            // Increment the date by one day
            $currentDate->modify('+1 day');
        }
    
        // Return the response
        return response()->json([
            'message' => 'The trip was created successfully',
            'trip_id' => $trip->id,
        ], 200);
    }

    //////////////////////////////////////////////

    public function getUserPlane($trip_id)
    {
        // Retrieve the trip
        $trip = Trip::find($trip_id);
    
        if (!$trip) {
            return response()->json([
                'message' => 'Trip not found',
            ], 404);
        }
    
        // Retrieve the ticket for the trip
        $ticket = BookingTicket::where('trip_id', $trip_id)->first();
    
        if (!$ticket) {
            return response()->json([
                'message' => 'Booking ticket not found for this trip',
            ], 404);
        }
    
        // Retrieve the hotel bookings for the trip
        $rooms = BookingHotel::where('trip_id', $trip_id)->get();
    
        // Calculate the total price of the rooms
        $totalPrice = $rooms->sum('price');
    
        // Calculate the final price
        $finalPrice = $totalPrice + $ticket->price;
    
        // Retrieve the trip days and associated trip day places
        $tripDays = TripDay::where('trip_id', $trip_id)->with(['tripDayPlace.tourismPlace'])->get();
    
        // Return the response
        return response()->json([
            'Ticket' => $ticket,
            'Hotels' => $rooms,
            'TotalRoomPrice' => $totalPrice,
            'TourismPlaces' => $tripDays,
            'FinalPrice' => $finalPrice,
        ]);
    }
}
