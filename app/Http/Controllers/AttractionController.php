<?php

namespace App\Http\Controllers;

use App\Models\Attraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class AttractionController extends Controller
{
    public function addAttractions(Request $request) {
       
        $attr = $request->validate([
            'publicTrip_id' => 'required|exists:public_trips,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,bmp|max:4096',
            'description' => 'required|string',
            'discount_points' => 'required|integer',
            'type' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->discount_points > 0 && $value !== 'Points Discount') {
                        $fail('The type must be "Points Discount" when the discount_points is greater than 0.');
                    }
                    if ($request->discount_points == 0 && !in_array($value, ['Discount On The Ticket', 'Special Event'])) {
                        $fail('The type must be "Discount On The Ticket" or "Special Event" when the discount_points is 0.');
                    }
                }
            ],
        ]);
    
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('uploads/'), $imageName);
            $imageUrl = URL::asset('uploads/' . $imageName);
        } else {
            $imageUrl = null;
        }
    
        $attraction = Attraction::create([
            'publicTrip_id' => $attr['publicTrip_id'],
            'image' => $imageUrl,
            'description' => $attr['description'],
            'discount_points' => $attr['discount_points'],
            'type' => $attr['type'],
        ]);
    
        return response()->json([
            'message' => 'The attraction was created successfully.',
            'attraction' => $attraction,
        ], 200);
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
        'publicTrip_id' => 'required|exists:public_trips,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,bmp|max:4096',
        'description' => 'required|string',
        'discount_points' => 'required|integer',
        'type' => [
            'required',
            function ($attribute, $value, $fail) use ($request) {
                if ($request->discount_points > 0 && $value !== 'Points Discount') {
                    $fail('The type must be "Points Discount" when the discount_points is greater than 0.');
                }
                if ($request->discount_points == 0 && !in_array($value, ['Discount On The Ticket', 'Special Event'])) {
                    $fail('The type must be "Discount On The Ticket" or "Special Event" when the discount_points is 0.');
                }
            }
        ],
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

public function displayAttraction($attraction_id)
{
    $Attraction = Attraction::find($attraction_id);

    if (!$Attraction) {
        return response([
            'message' => 'Attraction not found'
        ], 403); 
    }

    $Attraction->display = $Attraction->display ? false : true;
    $Attraction->save();
    return response()->json([
        'display' => $Attraction->display,
    ]);
}

public function getAttractions()
{
    $attractions = Attraction::where('display', true)
        ->get()
        ->map(function ($attraction) {
            $attraction->discount = $attraction->publicTrip->discountType;
            unset($attraction->publicTrip);
            return $attraction;
        });

    return response()->json([
        'attractions' => $attractions,
    ]);
}

}
