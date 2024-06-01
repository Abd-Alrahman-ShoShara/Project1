<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use App\Models\PublicTrip;
use App\Models\publicTripClassification;
use App\Models\PublicTripPlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PublicTripController extends Controller
{
    public function createPublicTrip(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,bmp|max:4096',
            'description' => 'required',
            'dateOfTrip' => 'required|date|after:today',
            'dateEndOfTrip' => 'required|date|after:dateOfTrip',
            'classifications.*' => 'required|string',
            'activities.*' => 'required|string',
            'citiesHotel_id' => 'required|integer'
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
        $publicTripPlaces=[];
        foreach ($request->activities as $activitie) {
            $publicTripPlace = PublicTripPlace::create([
                'tourismPlaces_id' => $activitie,
                'publicTrip_id' => $publicTrip->id,
            ]);
            $publicTripPlaces[]=$publicTripPlace;
        }
        if ($publicTrip) {
            return response()->json([
                'messaga' => 'created successfully',
                'publicTrip' => $publicTrip,
                'publicTripPlaces' => $publicTripPlaces,
            ]);
        }
    }


    public function selectTourismPlaces($publicTrip_id){

    }
}
