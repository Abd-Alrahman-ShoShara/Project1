<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use App\Models\PublicTrip;
use App\Models\publicTripClassification;
use App\Models\PublicTripPlace;
use App\Models\TripPoint;
use App\Models\UserPublicTrip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PublicTripController extends Controller
{
    public function addPublicTrip(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,bmp|max:4096',
            'description' => 'required',
            'dateOfTrip' => 'required|date|after:today',
            'dateEndOfTrip' => 'required|date|after:dateOfTrip',
            'classifications.*' => 'required|string',
            'activities.*' => 'required|string',
            'citiesHotel_id' => 'required|integer',

        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('uploads/'), $imageName);
            $imageUrl = URL::asset('uploads/' . $imageName);
        } else {
            $imageUrl = null;
        }
        $publicTrip = PublicTrip::create([
            'name' => $request->name,
            'image' => $imageUrl,
            'description' => $request->description,
            'citiesHotel_id' => $request->citiesHotel_id,
            'dateOfTrip' => $request->dateOfTrip,
            'dateEndOfTrip' => $request->dateEndOfTrip,
        ]);
        foreach ($request->classifications as $classification) {
            PublicTripClassification::create([
                'classification_id' => $classification,
                'publicTrip_id' => $publicTrip->id,
            ]);
        }
        $publicTripPlaces = [];
        foreach ($request->activities as $activitie) {
            $publicTripPlace = PublicTripPlace::create([
                'tourismPlaces_id' => $activitie,
                'publicTrip_id' => $publicTrip->id,
            ]);
            $publicTripPlaces[] = $publicTripPlace;
        }


        if ($publicTrip) {
            return response()->json([
                'messaga' => 'created successfully',
                'publicTrip' => $publicTrip,
                'publicTripPlaces' => $publicTripPlaces,
            ]);
        }
    }

    public function updatePublicTrip(Request $request, $publicTrip_id)
    {
        // Find the PublicTrip record
        $publicTrip = PublicTrip::findOrFail($publicTrip_id);

        // Validate the request data
        $attr = $request->validate([
            'name' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,bmp|max:4096',
            'description' => 'required|string',
            'dateOfTrip' => 'required|date|after:today',
            'dateEndOfTrip' => 'required|date|after:dateOfTrip',
            'classifications.*' => 'required|string',
            'activities.*' => 'required|string',
            'citiesHotel_id' => 'required|integer',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($publicTrip->image && file_exists(public_path('uploads/' . basename($publicTrip->image)))) {
                unlink(public_path('uploads/' . basename($publicTrip->image)));
            }

            // Upload the new image
            $imageName = time() . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('uploads/'), $imageName);
            $imageUrl = URL::asset('uploads/' . $imageName);
        } else {
            $imageUrl = $publicTrip->image;
        }

        // Update the PublicTrip record
        $publicTrip->update([
            'name' => $attr['name'],
            'image' => $imageUrl,
            'description' => $attr['description'],
            'dateOfTrip' => $attr['dateOfTrip'],
            'dateEndOfTrip' => $attr['dateEndOfTrip'],
            'citiesHotel_id' => $attr['citiesHotel_id'],
        ]);

        // Update classifications
        PublicTripClassification::where('publicTrip_id', $publicTrip->id)->delete();
        foreach ($attr['classifications'] as $classification) {
            PublicTripClassification::create([
                'classification_id' => $classification,
                'publicTrip_id' => $publicTrip->id,
            ]);
        }

        // Update activities
        PublicTripPlace::where('publicTrip_id', $publicTrip->id)->delete();
        foreach ($attr['activities'] as $activity) {
            PublicTripPlace::create([
                'tourismPlaces_id' => $activity,
                'publicTrip_id' => $publicTrip->id,
            ]);
        }

        // Return response
        return response()->json([
            'message' => 'Public trip updated successfully',
            'publicTrip' => $publicTrip,
        ], 200);
    }


    public function getPublicTrips()
    {

        $publicTrips = PublicTrip::all();
        return response()->json([
            'publicTrips' => $publicTrips,
        ], 200);
    }

    public function addPointsToTrip(Request $request, $publicTrip_id)
    {
        $request->validate([
            'city_id' => 'required|integer',
            'numberOfTickets' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $tripPoint = TripPoint::where([
            'city_id' => $request->city_id,
            'publicTrip_id' => $publicTrip_id,
        ])->first();

        if ($tripPoint) {
            return response()->json([
                'message' => 'Trip point already added',
            ], 422);
        }

        $point = TripPoint::create([
            'publicTrip_id' => $publicTrip_id,
            'city_id' => $request->city_id,
            'numberOfTickets' => $request->numberOfTickets,
            'price' => $request->price,
        ]);

        return response()->json([
            'message' => 'Trip point created successfully',
            'tripPoints' => $point,
        ], 201);
    }

    public function deletePublicTrip($publicTrip_id)
    {
        $publicTripID = PublicTrip::find($publicTrip_id);
        if (!$publicTripID) {
            return response()->json([
                'message' => 'trip not found.'
            ], 404);
        }
        $publicTrip = PublicTrip::where('id', $publicTrip_id)->where('display', false)->delete();

        if (!$publicTrip) {
            $tripPoints = TripPoint::where('publicTrip_id', $publicTrip_id)->get();

            foreach ($tripPoints as $tripPoint) {
                $trueFalse = UserPublicTrip::where('tripPoint_id', $tripPoint->id)->first();
                if ($trueFalse) {
                    return response()->json([
                        'message' => 'you cannot delete the trip.'
                    ]);
                }
            }
            PublicTrip::where('id', $publicTrip_id)->delete();
        }

        return response()->json([
            'message' => 'deleted successfully.'
        ]);
    }

    public function getPublicTripInfo($publicTrip_id)
    {
        return response([
            'publicTrip' => PublicTrip::where('id', $publicTrip_id)->with('publicTripPlace.tourismPlace','citiesHotel')->get(),
        ]);
    }

    public function getPublicTripPoints($publicTrip_id)
    {
        return response([
            'publicTripPoints' => TripPoint::where('publicTrip_id', $publicTrip_id)->with('city')->get(),
        ]);
    }
    public function getPointInfo($point_id)
    {
        return response([
            'TripPoint' => TripPoint::where('id', $point_id)->with('city')->get(),
        ]);
    }

    public function deletePoint($point_id)
    {
        $point = TripPoint::find($point_id);

        if (!$point) {
            return response()->json(['message' => 'point is not found'], 404);
        }
        $point->delete();
        return response()->json(['message' => ' deleted successfully'], 200);
    }

    public function updatePoint(Request $request, $point_id)
    {
        $point = TripPoint::find($point_id);

        $request->validate([
            'city_id' => 'required|integer',
            'numberOfTickets' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $point->update([
            'city_id' => $request->city_id,
            'numberOfTickets' => $request->numberOfTickets,
            'price' => $request->price,
        ]);
        return response()->json([
            'message' => ' the city updated successfully',
            'point' => $point,
        ], 200);
    }


    ////////////////////////////////////////// flutter function /////////////


    public function allPublicTrips(Request $request)
    {
        $attrs = $request->validate([
            'classification_id' => 'sometimes|integer',
        ]);

        if ($request->has('classification_id')) {
            $classification = $attrs['classification_id'];

            $theTrips = PublicTrip::
                whereHas('publicTripClassification', function ($query) use ($classification) {
                    $query->where('classification_id', $classification);
                })
                ->get()->where('display', true);

            if ($theTrips->isEmpty()) {
                return response()->json([
                    'message' => 'There are no trips for the specified classification ID.',
                ]);
            }

            return response()->json([
                'theTrips' => $theTrips,
            ]);
        } else {
            $theTrips = PublicTrip::where('display', true)->get();

            return response()->json([
                'theTrips' => $theTrips,
            ]);
        }
    }
    public function displayPublicTrip($publicTrip_id){
        $publicTrip=PublicTrip::find($publicTrip_id);

        if(!$publicTrip)
        {
            return response([
                'message'=>'publicTrip not found'
            ],403);
        }

        $publicTrip->display = $publicTrip->display?false:true;
        $publicTrip->save();
        return response()->json([
            'display' => $publicTrip->display,
        ]);
    }



}
