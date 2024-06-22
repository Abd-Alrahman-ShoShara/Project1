<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripPoint extends Model
{
    use HasFactory;

    protected $fillable=[
        'city_id',
        'publicTrip_id',
        'numberOfTickets',
        'price',
    ];
    protected $hidden=[
        'created_at',
        'updated_at',
    ];
    public function userPublicTrip(){
        return $this->hasMany(UserPublicTrip::class,'tripPoint_id');
    }
    public function publicTrip(){
        return $this->belongsTo(PublicTrip::class,'publicTrip_id');
    }
    public function city(){
        return $this->belongsTo(City::class);
    }


}
