<?php

namespace App\Http\Controllers;

use App\Models\BookingTicket;
use App\Models\Ticket;
use App\Models\Trip;
use Illuminate\Http\Request;

class BookingTicketController extends Controller
{
    
    public function choseTicket($trip_id,$ticket_id){
        $trip = Trip::find($trip_id);
        $ticket = Ticket::find($ticket_id);
        $finalPrice = $trip->numOfPersons * $ticket->price; 
        $TokenTicket = BookingTicket::create([
        'trip_id'=>$trip_id,
        'ticket_id'=>$ticket_id,
        'price'=>$finalPrice,
        ]);
        return response()->json([
            'message'=> ' added to your plane',
            'The Ticket_id :'=>$TokenTicket,
        ]);
    }
    
}
