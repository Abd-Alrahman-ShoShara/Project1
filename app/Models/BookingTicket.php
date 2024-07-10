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

    protected $hidden=[
        'created_at',
        'updated_at',
    ];
    use HasFactory;

    public function trip(){
        return $this->belongsTo(Trip::class,'trip_id');
    }

    public function ticket(){
        return $this->belongsTo(Ticket::class,'ticket_id');
    }

    public function bookingTrip(){
        return $this->hasOne(BookingTripe::class);
    }
}
