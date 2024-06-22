<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublicTripPlace extends Model
{
    use HasFactory;
    protected $fillable=[
        'tourismPlaces_id',
        'publicTrip_id',
    ];

    protected $hidden=[
        'created_at',
        'updated_at',
    ];
    public function tourismPlace(){
        return $this->belongsTo(TourismPlace::class,'tourismPlaces_id');
    }
    public function publicTrip(){
        return $this->belongsTo(PublicTrip::class);
    }
}
