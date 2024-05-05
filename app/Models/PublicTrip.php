<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicTrip extends Model
{
    use HasFactory;
    public function tourismPlace(){
        return $this->belongsTo(TourismPlace::class);
    }     public function citiesHotel(){
        return $this->belongsTo(CitiesHotel::class);
    } 
    public function tripPoint(){
        return $this->hasMany(TripPoint::class);
    } 
    public function attraction(){
        return $this->hasMany(Attraction::class);
    } 
}
