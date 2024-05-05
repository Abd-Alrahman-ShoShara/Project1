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
            'message'=> ' the city created successfully',
            'airport'=> $airport->id,
        ],200);
    } 
    public function getAirportFrom($id){
        $trip = Trip::find($id);
        $city = $trip->from;
        $airports=Airport::where('city_id',$city)->get();
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
    public function getAirportTo($id){
        $trip = Trip::find($id);
        $city = $trip->to;
        
        $airports=Airport::where('city_id',$city)->get();
        
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
}
