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
            'airport'=> $airline->id,
        ],200);
    } 
}
