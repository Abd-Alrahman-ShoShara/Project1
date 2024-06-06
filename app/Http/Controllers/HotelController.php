<?php

namespace App\Http\Controllers;

use App\Models\Airport;
use App\Models\CitiesHotel;
use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function addHotel(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|string|unique:hotels,name',
            'rate' => 'required|numeric|between:1,5',

        ]);
        $hotel = Hotel::create([

            'name' => $attr['name'],
            'rate' => $attr['rate'],
        ]);
        return response()->json([
            'message' => ' the hotel created successfully',
            'hotel' => $hotel->id,
        ], 200);
    }

    
    public function allHotel()
    {
        $hotels = Hotel::all();
        return response()->json([
            'hotel' => $hotels,
        ], 200);
    }

    public function deleteHotel($hotel_id)
    {
        $hotel = Hotel::find($hotel_id);
        if (!$hotel) {
            return response()->json(['message' => 'hotel is not found'], 404);
        }
        $hotel->delete();

        return response()->json(['message' => ' deleted successfully'], 200);
    }

    public function getHotelInfo($hotel_id)
    {
        return response([
            'airpot' => Hotel::find($hotel_id),
        ]);
    }
    public function updateHotel(Request $request, $hotel_id)
    {
        $hotel = Hotel::find($hotel_id);

        $attr = $request->validate([
            'name' => 'required|string|unique:hotels,name,' . $hotel_id,
            'rate' => 'required|numeric|between:1,5',
        ]);
        $hotel->update([
            'name' => $attr['name'],
            'rate' => $attr['rate'],
        ]);
        return response()->json([
            'message' => ' the hotel updated successfully',
            'hotel' => $hotel,
        ], 200);
    }
}
