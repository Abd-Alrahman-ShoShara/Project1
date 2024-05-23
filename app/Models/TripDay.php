<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripDay extends Model
{
    use HasFactory;

    protected $fillable =[
       'trip_id',
       'date', 
    ];
    
    protected $hidden=[
        'created_at',
        'updated_at',
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    /**
     * Get the places associated with the plan day.
     */
    public function tripDayPlace()
    {
        return $this->hasMany(TripDayPlace::class,'tripDay_id');
    }
}
