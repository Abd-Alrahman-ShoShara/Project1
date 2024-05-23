<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripPoint extends Model
{
    use HasFactory;
    
    protected $hidden=[
        'created_at',
        'updated_at',
    ];
    public function userPublicTrip(){
        return $this->hasMany(UserPublicTrip::class);
    }
    public function publicTrip(){
        return $this->belongsTo(PublicTrip::class);
    }
    public function city(){
        return $this->belongsTo(City::class);
    }


}
