<?php

namespace App\Http\Controllers;

use App\Models\TripDay;
use Illuminate\Http\Request;

class TripDayController extends Controller
{   
 public function getTripDays($trip_id){
    return response()->json([
        'Days'=>TripDay::where('trip_id',$trip_id)->get()->sortBy('date'),
    ]);
 }   
}
