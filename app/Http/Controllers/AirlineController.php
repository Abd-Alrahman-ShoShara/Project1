<?php

namespace App\Http\Controllers;

use App\Models\Airline;
use Illuminate\Http\Request;

class AirlineController extends Controller
{
    public function addAirLine(Request $request){
        $attr =$request->validate([
            'name'=>'required|unique:airLines',

        ]);
        $airline = Airline::create([
            
            'name'=>$attr['name'],
            
        ]);
        return response()->json([
            'message'=> ' the airline created successfully',
            'airline'=> $airline->id,
        ],200);
    } 
    public function allAirlines(){
        $airlines=Airline::all();
        return response()->json([
            'airline'=> $airlines,
        ],200);
        
    }
}
