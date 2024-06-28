<?php

namespace App\Http\Controllers;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\TripDayPlace;

class TripDayPlaceController extends Controller
{
    public function addPlane(Request $request ){
        $validator = Validator::make($request->all(), [
            'planes' => 'required|array',
            'planes.*.tripDay_id' => 'required|integer|exists:trip_days,id',
            'planes.*.places' => 'required|array',
            'planes.*.places.*' => 'required|integer|exists:tourism_places,id',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Retrieve the validated input
        $planes = $request->input('planes');
        $createdTripDayPlaces = [];

        foreach ($planes as $plane) {
            $tripDay_id = $plane['tripDay_id'];
            $placesArray = $plane['places'];
            foreach ($placesArray as $tourismPlace_id) {
                $tripDayPlace = TripDayPlace::create([
                    'tripDay_id' => $tripDay_id,
                    'tourismPlace_id' => $tourismPlace_id,
                ]);
                $createdTripDayPlaces[] = $tripDayPlace;
            }
        }
        return response()->json([
            'message' => 'The plane created successfully',
            'Planes' => $createdTripDayPlaces,
        ],200);
    }
     
}
