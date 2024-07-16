<?php

namespace App\Http\Controllers;

use App\Models\Attraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class AttractionController extends Controller
{
    public function addAttractions(Request $request){
        $attr = $request->validate([
            'publicTrip_id' => 'required|exists:publicTrips,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,bmp|max:4096',
            'description' => 'required|string',
            'discount_points' => 'required|integer',
            'type' => 'required|in:Discount On The Ticket,Points Discount,Special Event',
        ]);
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('uploads/'), $imageName);
            $imageUrl = URL::asset('uploads/' . $imageName);
        } else {
            $imageUrl = null;
        }
        $attraction = Attraction::create([
            'publicTrip_id'=>$attr['publicTrip_id'],
            'image' => $imageUrl,
            'description'=>$attr['description'],
            'discount_points'=>$attr['discount_points'],
            'type'=>$attr['type'],
        ]);
        return response()->json([
            'message'=> ' the attraction created successfully',
            'attraction'=> $attraction,
        ],200);
    }
    
    
    public function allAttractions()
    {
        $Attractions = Attraction::all();
        return response()->json([
            'Attraction' => $Attractions,
        ], 200);
    }

    public function deleteAttraction($attraction_id)
    {
        $Attraction = Attraction::find($attraction_id);
        if (!$Attraction) {
            return response()->json(['message' => 'Attraction is not found'], 404);
        }
        $Attraction->delete();

        return response()->json(['message' => ' deleted successfully'], 200);
    }
    public function getAttractionInfo($attraction_id)
    {
        return response([
            'theAttraction' => Attraction::find($attraction_id),
        ],200);
    }
    public function updateAttraction(Request $request, $attraction_id)
{
    $Attraction = Attraction::find($attraction_id);

    
    $attr = $request->validate([
        'publicTrip_id' => 'required|exists:publicTrips,id',
        'image' => 'image|mimes:jpeg,png,jpg,gif,bmp|max:4096',
        'description' => 'required|string',
        'discount_points' => 'required|integer',
        'type' => 'required|in:Discount On The Ticket,Points Discount,Special Event',
    ]);

    
    if ($request->hasFile('image')) {
        if ($Attraction->image && file_exists(public_path($Attraction->image))) {
            unlink(public_path($Attraction->image));
        }
        
        $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->move(public_path('uploads/'), $imageName);
        $imageUrl = 'uploads/' . $imageName; 
    } else {
        $imageUrl = $Attraction->image;
    }

    $Attraction->update([
        'publicTrip_id'=>$attr['publicTrip_id'],
        'image' => $imageUrl,
        'description'=>$attr['description'],
        'discount_points'=>$attr['discount_points'],
        'type'=>$attr['type'],
    ]);

    return response()->json([
        'message' => 'Attraction updated successfully',
        'Attraction' => $Attraction,
    ], 200);
}
}
