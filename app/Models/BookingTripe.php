<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingTripe extends Model
{
    use HasFactory;
    public function trip(){
        return $this->belongsTo(Trip::class);
    } 
       public function bookingHotel(){
        return $this->belongsTo(BookingHotel::class);
    }  
      public function bookingTicket(){
        return $this->belongsTo(BookingTicket::class);
    }
}
