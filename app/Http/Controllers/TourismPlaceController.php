<?php

namespace App\Http\Controllers;

use App\Models\TourismPlace;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

use function PHPSTORM_META\type;

class TourismPlaceController extends Controller
{
    public function addTourismPlace(Request $request, $city_id)
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
        $class = $request->validate([
            'type' => 'nullable|string',
        ]);
        $trip = Trip::find($trip_id);

        if (!$trip) {
            return response()->json([
                'message' => 'Trip not found'
            ], 404);
        }
        $toCity = $trip->to;
        if (empty($class['type'])) {
            $activities = TourismPlace::where('city_id', $toCity)->get();
        } else {
            $activities = TourismPlace::where('city_id', $toCity)
                ->where('type', $class['type'])
                ->get();
        }
        if ($activities->isEmpty()) {
            return response()->json([
                'message' => 'There are no places to show'
            ], 404);
        }
        foreach ($activities as $activitie) {
            $activitie->images = json_decode($activitie->images, true);
        }
        return response()->json([
            'activities' => $activities,
        ]);
    }

    public function searchTourismPlaces(Request $request, $trip_id)
    {
        $class = $request->validate([
            'type' => 'nullable|string',
            'search' => 'sometimes|string'
        ]);

        $trip = Trip::find($trip_id);

        if (!$trip) {
            return response()->json([
                'message' => 'Trip not found'
            ], 404);
        }

        $toCity = $trip->to;

        $activities = TourismPlace::where('city_id', $toCity);

        if (!empty($class['type'])) {
            $activities->where('type', $class['type']);
        }

        if ($request->has('search')) {
            $activities->where('name', 'like', '%' . $class['search'] . '%');
        }

        $activities = $activities->get();

        if ($activities->isEmpty()) {
            return response()->json([
                'message' => 'There are no places to show'
            ], 404);
        }

        foreach ($activities as $activity) {
            $activity->images = json_decode($activity->images, true);
        }

        return response()->json([
            'activities' => $activities,
        ]);
    }

    public function deleteTourismPlace($tourismPlace_id)
    {
        $tourismPlace = TourismPlace::find($tourismPlace_id);
        if (!$tourismPlace) {
            return response()->json(['message' => 'hotel is not found'], 404);
        }
        $tourismPlace->delete();

        return response()->json(['message' => ' deleted successfully'], 200);
    }


    public function getTourismPlaceInfo($tourismPlace_id)
    {
        $tourismPlace = TourismPlace::where('id', $tourismPlace_id)->with('city')->first();

        $tourismPlace->images = json_decode($tourismPlace->images, true);
        return response([
            'tourismPlace' => $tourismPlace,
        ]);
    }

    public function updateTourismPlace(Request $request, $tourismPlace_id)
    {

        $tourismPlace = TourismPlace::findOrFail($tourismPlace_id);

        $attr = $request->validate([
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,bmp|max:4096',
            'name' => 'required|unique:tourism_places,name,' . $tourismPlace->id,
            'description' => 'required',
            'openingHours' => 'required',
            'recommendedTime' => 'required',
            'type' => 'nullable',
        ]);

        $imageUrls = [];
        if ($request->hasFile('images')) {
            $oldImages = json_decode($tourismPlace->images, true);
            if ($oldImages) {
                foreach ($oldImages as $oldImage) {
                    if (file_exists(public_path($oldImage))) {
                        unlink(public_path($oldImage));
                    }
                }
            }
            foreach ($request->file('images') as $key => $image) {
                $imageName = time() . $key . '.' . $image->extension();
                $image->move(public_path('uploads/'), $imageName);
                $imageUrls[] = URL::asset('uploads/' . $imageName);
            }
        } else {
            $imageUrls = json_decode($tourismPlace->images, true);
        }


        $tourismPlace->update([
            'images' => $imageUrls ? json_encode($imageUrls) : null,
            'name' => $attr['name'],
            'description' => $attr['description'],
            'openingHours' => $attr['openingHours'],
            'recommendedTime' => $attr['recommendedTime'],
            'type' => $request->type,

        ]);

        // Return response
        return response()->json([
            'message' => 'The tourism place updated successfully',
            'tourismPlace' => $tourismPlace,
        ], 200);
    }

    public function searchActivity($city)
    {
        $activities = TourismPlace::whereHas('city', function ($query) use ($city) {
            $query->where('name', 'like', '%' . $city . '%');
        })->with('city')->get();
        foreach ($activities as $activitie) {
            $activitie->images = json_decode($activitie->images, true);
        }
        return response()->json([
            'activity'=>$activities
        ]);
        // $activities->where('name', 'like', '%' . $class['search'] . '%');
    }
}
