<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable =[
        'user_id',
        'from',
        'to',
        'dateOfTrip',
        'dateEndOfTrip',
        'completed',
        'numOfPersons',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function city(){
        return $this->belongsTo(City::class);
    }
    public function bookingHotel(){
        return $this->hasOne(BookingHotel::class);
    }
    public function bookingTicket(){
        return $this->hasOne(BookingTicket::class);
    }
    public function bookingTrip(){
        return $this->hasOne(BookingTripe::class);
    }
    public function tripDay()
    {
        return $this->hasMany(TripDay::class);
    }
    



}
