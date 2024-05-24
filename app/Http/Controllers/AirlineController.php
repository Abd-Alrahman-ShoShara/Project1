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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,bmp|max:4096',
        ]);

        // Store the uploaded image
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('uploads/'), $imageName);
            $imageUrl = URL::asset('uploads/' . $imageName); 
        } else {
            $imageUrl = null;
        }

        // Create the airline
        $airline = Airline::create([
            'name' => $attr['name'],
            'image' => $imageUrl, // Store the image URL in the database
        ]);

        return response()->json([
            'message' => 'The airline and image were created successfully',
            'airline' => $airline,
        ], 200);
    }
    public function allAirlines(){
        $airlines=Airline::all();
        return response()->json([
            'airline'=> $airlines,
        ],200);
        
    }
}
