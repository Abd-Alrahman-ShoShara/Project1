<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;

    protected $fillable =[
        'name',
        'rate',
    ];
    protected $hidden=[
        'created_at',
        'updated_at',
    ];

    public function citiesHotel(){
        return $this->hasMany(CitiesHotel::class,'hotel_id');
    } 
}
