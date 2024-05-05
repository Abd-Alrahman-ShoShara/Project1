<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CitiesHotel extends Model
{
    protected $fillable=[
        'city_id',
        'hotel_id',
        'images',
        'description',
        'features',
        'avarageOfPrice',
        'review',
    ];

    public function city(){
        return $this->belongsTo(City::class);
    } 
    public function hotel(){
        return $this->belongsTo(Hotel::class);
    } 
    public function trip(){
        return $this->belongsTo(Trip::class);
    } 
    public function roomHotel(){
        return $this->hasMany(RoomHotel::class);
    } 
    public function publicTrip(){
        return $this->hasMany(PublicTrip::class);
    } 

    use HasFactory;
}
