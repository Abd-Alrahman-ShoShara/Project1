<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TourismPlace extends Model
{
    protected $fillable = [
        'description',
        'type',
        'city_id',
        'images',
        'name',
        'openingHours',
        'recommendedTime',

    ];
    use HasFactory;
    public function publicTrip(){
        return $this->hasMany(PublicTrip::class);
    }
    public function city(){
        return $this->belongsTo(City::class);
    }
    public function tripDayPlace()
    {
        return $this->hasMany(TripDayPlace::class);
    }
}
