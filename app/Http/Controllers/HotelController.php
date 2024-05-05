<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function addHotel(Request $request){
        $attr =$request->validate([
            'name'=>'required',
            'rate'=>'required',

        ]);
        $hotel = Hotel::create([
            
            'name'=>$attr['name'],
            'rate'=>$attr['rate'],
        ]);
        return response()->json([
            'message'=> ' the hotel created successfully',
            'hotel'=> $hotel->id,
        ],200);
    } 
}
