<?php

namespace App\Http\Controllers;

use App\Models\BookingTicket;
use App\Models\Ticket;
use App\Models\Trip;
use Illuminate\Http\Request;

class BookingTicketController extends Controller
{

    public function choseTicket($trip_id,$ticket_id){
        $true=BookingTicket::where('trip_id',$trip_id)->first();
        if(!$true){
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
        ],200);}
        return response()->json([
            'message'=> 'you have already booked',
        ],403);
    }

}
