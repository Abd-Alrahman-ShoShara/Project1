<?php

namespace App\Http\Controllers;

use App\Models\CitiesHotel;
use App\Models\Hotel;
use App\Models\Trip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class CitiesHotelController extends Controller
{
    public function addCitiesHotel(Request $request)
    {
        // Validate the request data
        $attr = $request->validate([
            'city_id' => 'required|integer',
            'hotel_id' => 'required|integer',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,bmp|max:4096',
            'description' => 'required|string',
            'features.*' => 'required|string',
            'avarageOfPrice' => 'required|numeric',
            'review' => 'required|json',
        ]);

        // Handle image uploads
        $imageUrls = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $imageName = time() . $key . '.' . $image->extension();
                $image->move(public_path('uploads/'), $imageName);
                $imageUrls[] = URL::asset('uploads/' . $imageName);
            }
        } else {
            $imageUrls = null;
        }

        // Create the CitiesHotel record
        $hotel = CitiesHotel::create([
            'city_id' => $attr['city_id'],
            'hotel_id' => $attr['hotel_id'],
            'description' => $attr['description'],
            'features' => json_encode($attr['features']),
            'review' => $attr['review'],
            'avarageOfPrice' => $attr['avarageOfPrice'],
            'images' => $imageUrls ? json_encode($imageUrls) : null,
        ]);

        // Return response
        return response()->json([
            'message' => 'The hotel created successfully',
            'hotel' => $hotel,
        ], 200);
    }

    public function cityHotels($trip_id){
        $trip = Trip::find($trip_id);
        $to = $trip->to;
        $hotels = CitiesHotel::where('city_id',$to)->with('hotel')->get();
        $hotels = $hotels->map(function ($citiesHotel) {
            $citiesHotel->features = json_decode($citiesHotel->features);
            $citiesHotel->review = json_decode($citiesHotel->review);
            $citiesHotel->images = json_decode($citiesHotel->images);
            return $citiesHotel;
        });
        return response()->json([
          
            'hotel'=> $hotels,
        ],200);
    }

    
}
