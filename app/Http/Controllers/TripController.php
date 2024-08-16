<?php

namespace App\Http\Controllers;

use App\Models\BookingHotel;
use App\Models\BookingTicket;
use App\Models\CitiesHotel;
use App\Models\PublicTrip;
use App\Models\RoomHotel;
use App\Models\Trip;
use App\Models\TripDay;
use App\Models\UserPublicTrip;
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
            'trip_id' => Trip::where('id', $trip->id)->with('fromCity', 'toCity')->get(),
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
        $ticket = BookingTicket::where('trip_id', $trip_id)->with('ticket', 'ticket.airLine','ticket.fromAirport','ticket.toAirport')->first();
        $ticketP = $ticket ? $ticket->price : 0;

        // if (!$ticket) {
        //     return response()->json([
        //         'message' => 'Booking ticket not found for this trip',
        //     ], 404);
        // }

        // Retrieve the hotel bookings for the trip
        $rooms = BookingHotel::where('trip_id', $trip_id)->get();

        if ($rooms->isEmpty()) {
            $theHotel = null;
            $totalPrice = 0;
        } else {
            $roomHotel_id = $rooms[0]->roomHotel_id;
            $roompmHotel = RoomHotel::find($roomHotel_id);
            $citiesHotel_id = $roompmHotel->citiesHotel_id;
            $theHotel = CitiesHotel::where('id', $citiesHotel_id)
                ->with('hotel')
                ->first();

            if ($theHotel) {
                $theHotel->features = json_decode($theHotel->features);
                $theHotel->review = json_decode($theHotel->review);
                $theHotel->images = json_decode($theHotel->images);
            }
            $totalPrice = $rooms->sum('price');
        }

        // Calculate the total price of the rooms

        // Calculate the final price
        $finalPrice = $totalPrice + $ticketP;

        // Retrieve the trip days and associated trip day places
        $tripDays = TripDay::where('trip_id', $trip_id)
            ->with(['tripDayPlace.tourismPlace'])
            ->get();

        // Decode images for each TripDay
        $tripDays = $tripDays->map(function ($tripDay) {
            foreach ($tripDay->tripDayPlace as $place) {
                if (isset($place->tourismPlace->images)) {
                    $place->tourismPlace->images = is_string($place->tourismPlace->images)
                        ? json_decode($place->tourismPlace->images) : $place->tourismPlace->images;
                }
            }
            return $tripDay;
        });

        // Return the response
        return response()->json([
            'Ticket' => $ticket,
            'Hotels' => $theHotel ? [$theHotel] : [],
            'TotalRoomPrice' => $totalPrice,
            'TourismPlaces' => $tripDays,
            'FinalPrice' => $finalPrice,
        ], 200);
    }

    public function allTrips()
    {
        $Trips = Trip::with('user', 'fromCity', 'toCity')->get();
        return response()->json([
            'Trips' => $Trips,
        ], 200);
    }

    public function cancelePrivateTripe($trip_id)
    {
        $cancelledTrip = Trip::find($trip_id);
        $cancelledTrip->state = 'cancelled';
        $cancelledTrip->save();
        $price = BookingHotel::where('trip_id', $trip_id)->sum('price');
        $returnPrice = 0.5 * $price;


        if ($cancelledTrip) {

            NotificationController::sendNotification( 'your trip canceled, '.$returnPrice.'$ has been added to your wallet',$cancelledTrip->user_id,$trip_id,'canceledPrivateTrip');

            return response()->json([
                'message' => 'cancelled successfully',
                'thePrice' => $price,
                'theReturnPrice' => $returnPrice,
            ]);
        }
    }
    public function getCancelledTrip()
    {
        $mm = function ($trip) {
            $name = $trip->toCity->name;
            $image = $trip->toCity->image;

            $trip->name = $name;
            $trip->image = $image;
            $trip->type = 'private';

            return $trip;
        };
        // $mm1= function ($trip) {
        //     $trip->name = $trip->tripPoint->publicTrip->name;
        //     $trip->image = $trip->tripPoint->publicTrip->image;
        //     $trip->dateOfTrip=$trip->tripPoint->publicTrip->dateOfTrip;
        //     $trip->dateEndOfTrip=$trip->tripPoint->publicTrip->dateEndOfTrip;
        //     $trip->type = 'publicBooking';

        //     return $trip;
        // };
        $cancelledPrivateTrip = Trip::where([['user_id', Auth::user()->id], ['state', 'cancelled']])
            ->get()->map($mm)->select('id', 'name', 'image', 'dateOfTrip', 'dateEndOfTrip', 'type');

        $cancelledPublicTrip = PublicTrip::whereHas('tripPoint.userPublicTrip', function ($query) {
            $query->where('user_id', Auth::user()->id)->where('state', 'cancelled');
        })->get()->map(function ($trip) {
            $trip->type = 'public';
            return $trip;
        })->select('id', 'name', 'image', 'dateOfTrip', 'dateEndOfTrip', 'type');

        $AllCancelledTrips = $cancelledPrivateTrip->concat($cancelledPublicTrip)->sortBy('id')->values();

        return response()->json([
            'AllTrips' => $AllCancelledTrips,
            // 'thePublicCanclledTrip:'=>$cancelledPublicTrip,
        ]);
    }

    public function getUnderConstructionTrip(){
        $UnderConstructionTrip=Trip::where([['state','UnderConstruction'],['user_id',Auth::user()->id]])
        ->get()->map(function ($trip) {
            $trip->image =$trip->toCity->image;
            return $trip;
        });

        return response()->json([
            'UnderConstructionTrip' => $UnderConstructionTrip,
        ]);
    }
}
