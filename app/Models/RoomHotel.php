<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomHotel extends Model
{
    protected $fillable =[
        'typeOfRoom',
        'description',
        'numberOfRoom',
        'price',
        'citiesHotel_id',
        
    ];
    use HasFactory;
    
    protected $hidden=[
        'created_at',
        'updated_at',
    ];
   
    public function citiesHotel(){
        return $this->belongsTo(CitiesHotel::class,'citiesHotel_id');
    } 
    public function boookingHotel(){
        return $this->hasMany(BookingHotel::class);
    } 

}
