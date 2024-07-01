<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingHotel extends Model
{

    protected $fillable=[
        'trip_id',
        'roomHotel_id',
        'numberOfRoom',
        'price',
        'checkIn',
        'checkOut',
    ];
    
    protected $hidden=[
        'created_at',
        'updated_at',
    ];
    use HasFactory;
    public function trip(){
        return $this->belongsTo(Trip::class);
    } 
    public function roomHotel(){
        return $this->belongsTo(RoomHotel::class,'roomHotel_id');
    } 
    public function bookingHotel(){
        return $this->hasOne(BookingHotel::class);
    } 
}
