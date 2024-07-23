<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\Trip;
use Illuminate\Http\Request;

class AirportController extends Controller
{
    //
    public function addAirport(Request $request){
        $attr =$request->validate([
            'name'=>'required|unique:airports',
            'city_id'=>'required',

        ]);
        $airport = Airport::create([
            'name'=>$attr['name'],
            'city_id'=>$attr['city_id'],
        ]);
        return response()->json([
            'message'=> ' the airport created successfully',
            'airport'=> $airport->id,
        ],200);
    } 
    public function getAirportFrom($trip_id){
        $trip = Trip::find($trip_id);
        $city = $trip->from;
        $airports=Airport::where('city_id',$city)->with('city')->get();
        if(!$airports){
            return response()->json([
                'message'=>'There is no airPorts to desplay'
            ],404);
        }
            return response()->json([
                'message'=>' the Airports is :',
                'airPorts'=>$airports,
            ],200);
        
    }
    public function getAirportTo($trip_id){
        $trip = Trip::find($trip_id);
        $city = $trip->to;
        
        $airports=Airport::where('city_id',$city)->with('city')->get();
        
        if(!$airports){
            return response()->json([
                'message'=>'There is no airPorts to desplay'
            ],404);
            }
            return response()->json([
                'message'=>' the Airports is :',
                'airPorts'=>$airports,
            ],200);
    }
    public function allAirports(){
        $airports=Airport::with('city')->get();
        return response()->json([
            'airports'=> $airports,
        ],200);
        
    }

    public function deleteAirport($airport_id){
        $airport =Airport::find($airport_id);
        if(!$airport){
            return response()->json(['message' => 'airpot is not found'], 404);
        }
        $airport->delete(); 

       return response()->json(['message' => ' deleted successfully'], 200);    
   }
   public function getAirportInfo($airport_id){
    return response([
        'airpot'=>Airport::where('id',$airport_id)->with('city')->first(),
    ]);
}
public function updateAirport(Request $request,$airport_id){
    $airport=Airport::find($airport_id);

    $attr =$request->validate([
        'name'=>'required|unique:airports,name,' .$airport->id,
        'city_id'=>'required|integer',
    
    ]);
    $airport->update([
        'name'=>$attr['name'],
        'city_id'=>$attr['city_id'],
    ]);
    return response()->json([
        'message'=> ' the city updated successfully',
        'airpot'=> $airport,
    ],200);
} 
}
