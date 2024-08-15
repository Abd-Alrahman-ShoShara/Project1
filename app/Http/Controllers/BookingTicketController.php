<?php

namespace App\Http\Controllers;

use App\Models\BookingTicket;
use App\Models\Ticket;
use App\Models\Trip;
use App\Models\TripDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingTicketController extends Controller
{

    public function choseTicket($trip_id, $ticket_id)
    {
        $true = BookingTicket::where('trip_id', $trip_id)->first();
        if (!$true) {
            $trip = Trip::find($trip_id);
            $ticket = Ticket::find($ticket_id);
            $finalPrice = $trip->numOfPersons * $ticket->price;
            $TokenTicket = BookingTicket::create([
                'trip_id' => $trip_id,
                'ticket_id' => $ticket_id,
                'price' => $finalPrice,
            ]);
            return response()->json([
                'message' => ' added to your plane',
                'The Ticket_id :' => $TokenTicket,
            ], 200);
        }
        return response()->json([
            'message' => 'you have already booked',
        ], 403);
    }


    public function updateTicket(Request $request, $bookingTicket_id)
    {

        $attr = $request->validate([
            'dateOfTrip' => 'required|date|after_or_equal:today',
            'dateEndOfTrip' => 'required|date|after:dateOfTrip',
            'numOfPersons' => 'required|integer|min:1',
            'airport_id1' => 'required|integer|exists:airports,id',
            'airport_id2' => 'required|integer|exists:airports,id',
            'typeOfTicket' => 'required|string',
            'roundOrOne_trip' => 'required|string|in:RoundTrip,OneWay',
        ]);
        $trip_id = BookingTicket::where('id', $bookingTicket_id)->first()->trip_id;

        $trip = Trip::where('id', $trip_id)->update([
            'dateOfTrip' => $attr['dateOfTrip'],
            'dateEndOfTrip' => $attr['dateEndOfTrip'],
            'numOfPersons' => $attr['numOfPersons'],
        ]);
        $alreadyTickets = Ticket::where([
            ['airport_id1', $attr['airport_id1']],
            ['airport_id2', $attr['airport_id2']],
            ['typeOfTicket', $attr['typeOfTicket']],
            ['roundOrOne_trip', $attr['roundOrOne_trip']],
            ['dateOfTicket', $attr['dateOfTrip']],
            ['dateEndOfTicket', $attr['dateEndOfTrip']],
        ])->with('airLine')->get();

        if ($alreadyTickets->isNotEmpty()) {


            return response()->json([
                'message' => 'There are already tickets',
                'numberOfFlights' => $alreadyTickets->count(),
                'tickets' => $alreadyTickets,
            ], 200);
        }

        $count = mt_rand(1, 5);
        $tickets = Ticket::factory()->count($count)->create([
            'airport_id1' => $attr['airport_id1'],
            'airport_id2' => $attr['airport_id2'],
            'typeOfTicket' => $attr['typeOfTicket'],
            'roundOrOne_trip' => $attr['roundOrOne_trip'],
            'dateOfTicket' => $request->dateOfTrip,
            'dateEndOfTicket' => $request->dateEndOfTrip,
        ]);


        if ($attr['roundOrOne_trip'] == 'RoundTrip') {
            foreach ($tickets as $ticket) {
                $ticket->price += $ticket->price * 0.5;
                $ticket->save();
            }
        }
        $ticket1 = Ticket::where([
            ['airport_id1', $attr['airport_id1']],
            ['airport_id2', $attr['airport_id2']],
            ['typeOfTicket', $attr['typeOfTicket']],
            ['roundOrOne_trip', $attr['roundOrOne_trip']],
            ['dateOfTicket', $attr['dateOfTrip']],
            ['dateEndOfTicket', $attr['dateEndOfTrip']],
        ])->with('airLine')->get();

        $currentDate = new \DateTime($attr['dateOfTrip']);
        $endDate = new \DateTime($attr['dateEndOfTrip']);

        // Loop through each day and create a TripDay entry
        while ($currentDate <= $endDate) {
            if (!TripDay::where('trip_id', $trip_id)
                ->where('date', $currentDate->format('Y-m-d'))
                ->first()) {
                TripDay::create([
                    'trip_id' => $trip_id,
                    'date' => $currentDate->format('Y-m-d'),
                ]);
            }
            $currentDate->modify('+1 day');
        }

        TripDay::where('trip_id', $trip_id)
            ->whereNotBetween('date', [$request->dateOfTrip, $request->dateEndOfTrip])
            ->delete();

        return response()->json([
            'message' => 'The ticket(s) created successfully',
            'numberOfFlights' => $ticket1->count(),
            'tickets' => $ticket1,
        ], 200);
    }
    public function updateBookingTicket($trip_id, $ticket_id)
    {
        $trip = Trip::find($trip_id);
        $ticket = Ticket::find($ticket_id);
        $finalPrice = $trip->numOfPersons * $ticket->price;
        $newBookingTicket = BookingTicket::where('trip_id', $trip_id)->update([
            'trip_id' => $trip_id,
            'ticket_id' => $ticket_id,
            'price' => $finalPrice,
        ]);

        return response()->json([
            'message' => ' your ticket is updated',
            'The Ticket_id :' => $newBookingTicket,
        ], 200);
    }
    public function deleteTicket($bookingTicket_id){
        $BookingTicket =BookingTicket::find($bookingTicket_id);
        if(!$BookingTicket){
            return response()->json(['message' => 'BookingTicket is not found'], 404);
        }
        $BookingTicket->delete();

       return response()->json(['message' => ' deleted successfully'], 200);
   }


}
