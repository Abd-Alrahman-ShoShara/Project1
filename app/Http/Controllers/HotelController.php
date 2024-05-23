<?php

namespace App\Http\Controllers;

use App\Models\CitiesHotel;
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
    
    public function allCitiesHotel()
    {
        // Retrieve all CitiesHotel records with their associated Hotel
        $hotels = CitiesHotel::with('hotel')->get();

        // Iterate over each CitiesHotel record and decode the required fields
        $hotels = $hotels->map(function ($citiesHotel) {
            $citiesHotel->features = json_decode($citiesHotel->features);
            $citiesHotel->review = json_decode($citiesHotel->review);
            $citiesHotel->images = json_decode($citiesHotel->images);
            return $citiesHotel;
        });

        return response()->json([
            'hotel' => $hotels,
        ], 200);
    }

}
