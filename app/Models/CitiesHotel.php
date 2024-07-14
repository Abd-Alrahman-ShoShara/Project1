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

    protected $hidden=[
        'created_at',
        'updated_at',
    ];

    public function city(){
        return $this->belongsTo(City::class,'city_id');
    }
    public function hotel(){
        return $this->belongsTo(Hotel::class,'hotel_id');
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
