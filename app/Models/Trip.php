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
    
    protected $hidden=[
        'created_at',
        'updated_at',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    // public function city(){
    //     return $this->belongsTo(City::class);
    // }
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
    public function fromCity()
    {
        return $this->belongsTo(City::class, 'from');
    }
    public function toCity()
    {
        return $this->belongsTo(City::class, 'to');
    }

}
