<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\TripDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    public function createTrip(Request $request){
        $attr =$request->validate([
            'from'=>'required',
            'to'=>'required',
            'dateOfTrip'=>'required|date',
            'dateEndOfTrip'=>'required|date',
            'numOfPersons'=>'required',
        ]);
        $trip = Trip::create([
            'user_id'=>Auth::user()->id,
            'from'=>$attr['from'],
            'to'=>$attr['to'],
            'dateOfTrip'=>$attr['dateOfTrip'],
            'dateEndOfTrip'=>$attr['dateEndOfTrip'],
            'numOfPersons'=>$attr['numOfPersons'],
        ]);

        // $tripDays=TripDay::creat([

        // ]);
        return response()->json([
            'message'=> ' the trip created successfully',
            'trip_id'=> $trip->id,
        ],200);
    } 
}
