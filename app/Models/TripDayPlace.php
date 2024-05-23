<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripDayPlace extends Model
{
    protected $fillable =[
        'tripDay_id',
        'tourismPlace_id', 
     ];
     
    protected $hidden=[
        'created_at',
        'updated_at',
    ];
    use HasFactory;
    public function tripDay()
    {
        return $this->belongsTo(TripDay::class);
    }

    /**
     * Get the tourism place associated with the plan day place.
     */
    public function tourismPlace()
    {
        return $this->belongsTo(TourismPlace::class,'tourismPlace_id');
    }
}
