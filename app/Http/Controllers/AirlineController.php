<?php

namespace App\Http\Controllers;

use App\Models\Airline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class AirlineController extends Controller
{
    public function addAirLine(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|unique:airlines',
            'image' => 'image|mimes:jpeg,png,jpg,gif,bmp|max:4096',
        ]);

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('uploads/'), $imageName);
            $imageUrl = URL::asset('uploads/' . $imageName);
        } else {
            $imageUrl = null;
        }

        $airline = Airline::create([
            'name' => $attr['name'],
            'image' => $imageUrl,
        ]);

        return response()->json([
            'message' => 'The airline and image were created successfully',
            'airline' => $airline,
        ], 200);
    }
    public function allAirlines()
    {
        $airlines = Airline::all();
        return response()->json([
            'airline' => $airlines,
        ], 200);
    }

    public function deleteAirline($airline_id)
    {
        $airline = Airline::find($airline_id);
        if (!$airline) {
            return response()->json(['message' => 'airline is not found'], 404);
        }
        $airline->delete();

        return response()->json(['message' => ' deleted successfully'], 200);
    }
    public function getAirlineInfo($airline_id)
    {
        return response([
            'theAirline' => Airline::find($airline_id),
        ]);
    }
    public function updateAirline(Request $request, $airline_id)
{
    $airline = Airline::find($airline_id);

    
    $validatedData = $request->validate([
        'name' => 'required|unique:airlines,name,' . $airline_id,
        'image' => 'image|mimes:jpeg,png,jpg,gif,bmp|max:4096',
    ]);

    
    if ($request->hasFile('image')) {
        if ($airline->image && file_exists(public_path($airline->image))) {
            unlink(public_path($airline->image));
        }

        
        $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(public_path('uploads/'), $imageName);
        $imageUrl = 'uploads/' . $imageName; 
    } else {
        $imageUrl = $airline->image;
    }

    $airline->update([
        'name' => $validatedData['name'],
        'image' => $imageUrl,
    ]);

    return response()->json([
        'message' => 'Airline updated successfully',
        'airline' => $airline,
    ], 200);
}
}
