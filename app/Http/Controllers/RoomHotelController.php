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
                'typeOfRoom'=>'required|string',
                'description'=>'required|string',
                'numberOfRoom'=>'required|integer',
                'price'=>'required|numeric',
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
    public function getRooms($citiesHotel_id){
        $roomHotel=RoomHotel::where('citiesHotel_id',$citiesHotel_id)->get();
        return response()->json([
            'numOfRoom'=> $roomHotel->count(),
            'room'=> $roomHotel,
        ],200);
    }

    public function deleteRoomHotel($roomHotel_id)
    {
        $roomHotel = RoomHotel::find($roomHotel_id);

        if (!$roomHotel) {
            return response()->json(['message' => 'roomHotel is not found'], 404);
        }
        $roomHotel->delete();

        return response()->json(['message' => ' deleted successfully'], 200);
    }

    public function getRoomHotelInfo($roomHotel_id)
    {
        return response([
            'roomHotel' => RoomHotel::find($roomHotel_id),
        ]);
    }
    public function updateRoomHotel(Request $request, $roomHotel_id)
    {
        $roomHotel = RoomHotel::find($roomHotel_id);

        $attr = $request->validate([
                'typeOfRoom'=>'required|string',
                'description'=>'required|string',
                'numberOfRoom'=>'required|integer',
                'price'=>'required|numeric',
        ]);
        $roomHotel->update([
            'typeOfRoom'=>$attr['typeOfRoom'],
            'description'=>$attr['description'],
            'numberOfRoom'=>$attr['numberOfRoom'],
            'price'=>$attr['price'],
        ]);
        return response()->json([
            'message' => ' the roomHotel updated successfully',
            'hotel' => $roomHotel,
        ], 200);
    }
}
