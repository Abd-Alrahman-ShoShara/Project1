<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Trip;
use Illuminate\Http\Request;
use Faker\Factory as Faker;

class TicketController extends Controller
{
    public function searchForTicket(Request $request, $trip_id)
    {
        $trip = Trip::find($trip_id);

        $attr = $request->validate([
            'airport_id1' => 'required',
            'airport_id2' => 'required',
            'typeOfTicket' => 'required',
            'roundOrOne_trip' => 'required',
        ]);

        $alreadyTicket = Ticket::where([
            ['airport_id1', $attr['airport_id1']],
            ['airport_id2', $attr['airport_id2']],
            ['typeOfTicket', $attr['typeOfTicket']],
            ['roundOrOne_trip', $attr['roundOrOne_trip']],
        ])->get();

        if ($alreadyTicket->isEmpty()) {
            $count = mt_rand(0, 3);

            $tickets = Ticket::factory()->count($count)->create([
                'airport_id1' => $attr['airport_id1'],
                'airport_id2' => $attr['airport_id2'],
                'typeOfTicket' => $attr['typeOfTicket'],
                'roundOrOne_trip' => $attr['roundOrOne_trip'],
                'dateOfTicket' => $trip->dateOfTrip,
                'dateEndOfTicket' => $trip->dateEndOfTrip,
            ]);
            if ($attr['roundOrOne_trip'] != 'OneWay') {
                foreach ($tickets as $ticket) {
                   
                    $ticket->price += $ticket -> price * 0.5;
                    $ticket->save();
                }
            }

            return response()->json([
                'message' => 'The ticket(s) created successfully',
                'tickets' => $tickets,
            ],200);
        }

        return response()->json([
            'message' => 'There are already tickets',
            'tickets' => $alreadyTicket,
        ],200);
    }
}
