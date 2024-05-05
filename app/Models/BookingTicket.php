<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingTicket extends Model
{
    protected $fillable=[
        'trip_id',
        'ticket_id',
        'price',

    ];
    use HasFactory;

    public function trip(){
        return $this->belongsTo(Trip::class);
    }     public function ticket(){
        return $this->belongsTo(Ticket::class);
    } 
    
    public function bookingTrip(){
        return $this->hasOne(BookingTripe::class);
    } 
}
