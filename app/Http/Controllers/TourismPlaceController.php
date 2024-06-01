<?php

namespace App\Http\Controllers;

use App\Models\TourismPlace;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

use function PHPSTORM_META\type;

class TourismPlaceController extends Controller
{
    public function addTourismPlaces(Request $request, $city_id)
    {
        $attr = $request->validate([
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,bmp|max:4096',
            'name' => 'required|unique:tourism_places',
            'description' => 'required',
            'openingHours' => 'required',
            'recommendedTime' => 'required',
            'type' => 'nullable',
        ]);

        $imageUrls = [];
        if ($request->hasFile('images')) {

            foreach ($request->images as $key => $value) {

                $imageName = time() . $key . '.' . $value->extension();
                $value->move(public_path('uploads/'), $imageName);
                $url = URL::asset('uploads/' . $imageName);
                $imageUrls[] = $url;
            }
        } else {
            $imageUrls = null;
        }
        $tourismPlace = TourismPlace::create([

            'images' => json_encode($imageUrls),
            'name' => $attr['name'],
            'description' => $attr['description'],
            'openingHours' => $attr['openingHours'],
            'recommendedTime' => $attr['recommendedTime'],
            'type' => $request->type,
            'city_id' => $city_id,
        ]);
        return response()->json([
            'message' => ' the tourismPlace created successfully',
            'tourismPlace' => $tourismPlace,
        ], 200);
    }

    /////////////////////////////////////////////////////////////
    public function getTourismPlacesWep($city_id)
    {
        $tourismPlaces = TourismPlace::where('city_id', $city_id)->get();

        // Decode the images attribute for each tourism place
        foreach ($tourismPlaces as $tourismPlace) {
            $tourismPlace->images = json_decode($tourismPlace->images, true);
        }

        return response()->json([
            'all_places' => $tourismPlaces,
        ]);
    }
    ////////////////////////////////////////////////////////////////

    public function getTourismPlaces(Request $request, $trip_id)
    {

        // Validate the request data
        $class = $request->validate([
            'type' => 'nullable|string',
        ]);

        // Find the trip
        $trip = Trip::find($trip_id);

        if (!$trip) {
            return response()->json([
                'message' => 'Trip not found'
            ], 404);
        }

        $toCity = $trip->to;

        // Get tourism places based on the city and optional type
        if (empty($class['type'])) {
            $activities = TourismPlace::where('city_id', $toCity)->get();
        } else {
            $activities = TourismPlace::where('city_id', $toCity)
                ->where('type', $class['type'])
                ->get();
        }


        // Check if activities are found
        if ($activities->isEmpty()) {
            return response()->json([
                'message' => 'There are no places to show'
            ], 404);
        }

        foreach ($activities as $activitie) {
            $activitie->images = json_decode($activitie->images, true);
        }

        // Return the activities
        return response()->json([
            'activities' => $activities,
        ]);
    }
}
