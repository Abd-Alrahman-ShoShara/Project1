<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicTrip extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'image',
        'description',
        'citiesHotel_id',
        'dateOfTrip',
        'dateEndOfTrip',
        'discountType',
        'display',
    ];

    protected $hidden=[
        'created_at',
        'updated_at',
    ];
    public function citiesHotel(){
        return $this->belongsTo(CitiesHotel::class,'citiesHotel_id');
    }
    public function tripPoint(){
        return $this->hasMany(TripPoint::class,'publicTrip_id');
    }
    public function attraction(){
        return $this->hasMany(Attraction::class,'publicTrip_id');
    }
    public function publicTripPlace(){
        return $this->hasMany(PublicTripPlace::class,'publicTrip_id');
    }
    public function publicTripClassification(){
        return $this->hasMany(PublicTripClassification::class,'publicTrip_id');
    }
    public function favorite()
    {
        return $this->hasMany(Favorite::class,'user_id');
    }
}
