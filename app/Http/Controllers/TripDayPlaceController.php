<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\TripDayPlace;

class TripDayPlaceController extends Controller
{
    public function addPlane(Request $request)
    {
        $validator = $request->validate([
            'planes' => 'required|array',
            'planes.*.tripDay_id' => 'required|integer|exists:trip_days,id',
            'planes.*.places' => 'required|array',
            'planes.*.places.*' => 'required|integer|exists:tourism_places,id',
        ]);

        // Retrieve the validated input
        $planes = $request->input('planes');
        $createdTripDayPlaces = [];

        foreach ($planes as $plane) {
            $tripDay_id = $plane['tripDay_id'];
            $placesArray = $plane['places'];

            foreach ($placesArray as $tourismPlace_id) {
                $existingTripDayPlace = TripDayPlace::where([
                    ['tripDay_id', $tripDay_id],
                    ['tourismPlace_id', $tourismPlace_id]
                ])->first();

                if (!$existingTripDayPlace) {
                    $tripDayPlace = TripDayPlace::create([
                        'tripDay_id' => $tripDay_id,
                        'tourismPlace_id' => $tourismPlace_id,
                    ]);
                    $createdTripDayPlaces[] = $tripDayPlace;
                }
            }
        }

        return response()->json([
            'message' => 'The plane created successfully',
            'Planes' => $createdTripDayPlaces,
        ], 200);
    }

    public function deleteActivities($tripDay_id)
    {
        $tripDay = TripDayPlace::where('tripDay_id', $tripDay_id)->delete();
        if (!$tripDay) {
            return response()->json(['message' => 'TripDayPlace is not found'], 404);
        }
        return response()->json(['message' => ' deleted successfully','tripDay_id:'=>$tripDay_id], 200);
    }
    public function deleteAllActivities($trip_id)
    {
        $trip = TripDayPlace::whereHas('tripDay',function($query) use ($trip_id){
            $query->where('trip_id',$trip_id);
        })->delete();
        if (!$trip) {
            return response()->json(['message' => 'TripDayPlace is not found'], 404);
        }
        return response()->json(['message' => ' deleted successfully'], 200);
    }
}
