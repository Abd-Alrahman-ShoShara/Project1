<?php

namespace App\Http\Controllers;

use App\Models\CitiesHotel;
use App\Models\RoomHotel;
use Illuminate\Http\Request;

class RoomHotelController extends Controller
{
    public function addRoomsHotel(Request $request,$citiesHotel_id){
        
        $citiesHotel = CitiesHotel::find($citiesHotel_id);
        if(!$citiesHotel){
            return response()->json([
                'message'=> ' the hotel dose not existe'
            ]);
        }
            $attr =$request->validate([
                'typeOfRoom'=>'required',
                'description'=>'required',
                'numberOfRoom'=>'required',
                'price'=>'required',
            ]);

        $room = RoomHotel::create([
            'citiesHotel_id'=>$citiesHotel_id,
            'typeOfRoom'=>$attr['typeOfRoom'],
            'description'=>$attr['description'],
            'numberOfRoom'=>$attr['numberOfRoom'],
            'price'=>$attr['price'],
        ]);
        return response()->json([
            'message'=> ' the room created successfully',
            'room'=> $room,
        ],200);
    } 
}
