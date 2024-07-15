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

    public function cityHotels($trip_id)
    {
        $trip = Trip::find($trip_id);
        $to = $trip->to;
        $hotels = CitiesHotel::where('city_id', $to)->with('hotel')->get();
        $hotels = $hotels->map(function ($citiesHotel) {
            $citiesHotel->features = json_decode($citiesHotel->features);
            $citiesHotel->review = json_decode($citiesHotel->review);
            $citiesHotel->images = json_decode($citiesHotel->images);
            return $citiesHotel;
        });

        return response()->json([
            'numberOfHotel:' => $hotels->count(),
            'hotel' => $hotels,
        ], 200);
    }
    public function cityHotelsSortBy(Request $request, $trip_id)
    {
        $attrs = $request->validate([
            'sortBy' => 'sometimes|in:Rating,Price High To Low,Price Low To High',
            'search' => 'sometimes|string'
        ]);

        $sortHotels = function ($hotels) use ($attrs) {
            if ($attrs['sortBy'] == 'Rating') {
                $hotels = $hotels->sortByDesc('hotel.rate');
            } elseif ($attrs['sortBy'] == 'Price High To Low') {
                $hotels = $hotels->sortByDesc('avarageOfPrice');
            } elseif ($attrs['sortBy'] == 'Price Low To High') {
                $hotels = $hotels->sortBy('avarageOfPrice');
            }

            return $hotels;
        };

        $trip = Trip::find($trip_id);
        $to = $trip->to;
        $hotels = CitiesHotel::where('city_id', $to)->with('hotel');

        if ($request->has('search')) {
            $hotels->whereHas('hotel', function ($query) use ($attrs) {
                $query->where('name', 'like', '%' . $attrs['search'] . '%');
            });
        }

        $hotels = $hotels->get();

        $hotels = $hotels->map(function ($citiesHotel) {
            $citiesHotel->features = json_decode($citiesHotel->features);
            $citiesHotel->review = json_decode($citiesHotel->review);
            $citiesHotel->images = json_decode($citiesHotel->images);
            return $citiesHotel;
        });

        if ($request->has('sortBy')) {
            $hotels = $sortHotels($hotels)->values();
        }

        return response()->json([
            'numberOfHotel' => $hotels->count(),
            'hotel' => $hotels,
        ], 200);
    }

    public function allCitiesHotel()
    {
        $hotels = CitiesHotel::with('hotel', 'city')->get();

        $hotels = $hotels->map(function ($citiesHotel) {
            $citiesHotel->features = json_decode($citiesHotel->features);
            $citiesHotel->review = json_decode($citiesHotel->review);
            $citiesHotel->images = json_decode($citiesHotel->images);
            return $citiesHotel;
        });

        return response()->json([
            'hotel' => $hotels,
        ], 200);
    }


    public function deleteCitiesHotel($citiesHotel_id)
    {
        $citiesHotel = CitiesHotel::find($citiesHotel_id);
        if (!$citiesHotel) {
            return response()->json(['message' => 'hotel is not found'], 404);
        }
        $citiesHotel->delete();

        return response()->json(['message' => ' deleted successfully'], 200);
    }


    public function getCitiesHotelInfo($citiesHotel_id)
    {
        $citiesHotel = CitiesHotel::where('id', $citiesHotel_id)->with('city', 'hotel')->first();


        $citiesHotel->images = json_decode($citiesHotel->images, true);

        $citiesHotel->features = json_decode($citiesHotel->features, true);

        $citiesHotel->review = json_decode($citiesHotel->review, true);

        return response([
            'citiesHotel' => $citiesHotel,
        ]);
    }

    public function updateCitiesHotel(Request $request, $citiesHotel_id)
    {
        // Find the CitiesHotel record
        $citiesHotel = CitiesHotel::findOrFail($citiesHotel_id);

        // Validate the request data
        $attr = $request->validate([
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
            // Delete old images
            $oldImages = json_decode($citiesHotel->images, true);
            if ($oldImages) {
                foreach ($oldImages as $oldImage) {
                    if (file_exists(public_path($oldImage))) {
                        unlink(public_path($oldImage));
                    }
                }
            }

            // Upload new images
            foreach ($request->file('images') as $key => $image) {
                $imageName = time() . $key . '.' . $image->extension();
                $image->move(public_path('uploads/'), $imageName);
                $imageUrls[] = URL::asset('uploads/' . $imageName);
            }
        } else {
            $imageUrls = json_decode($citiesHotel->images, true);
        }

        // Update the CitiesHotel record
        $citiesHotel->update([

            'hotel_id' => $attr['hotel_id'],
            'description' => $attr['description'],
            'features' => json_encode($attr['features']),
            'review' => $attr['review'],
            'avarageOfPrice' => $attr['avarageOfPrice'],
            'images' => $imageUrls ? json_encode($imageUrls) : null,
        ]);

        // Return response
        return response()->json([
            'message' => 'The hotel updated successfully',
            'hotel' => $citiesHotel,
        ], 200);
    }
}
