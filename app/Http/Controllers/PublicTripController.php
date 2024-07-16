<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use App\Models\Favorite;
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
        // Helper function to decode tourismPlace fields
        $mm = function ($trip) {
            $decodeTourismPlaceFields = function ($tourismPlace) {
                if ($tourismPlace) {
                    $tourismPlace->images = json_decode($tourismPlace->images);
                }
                return $tourismPlace;
            };
            // Decode specific attributes in cities_hotel
            if (isset($trip->citiesHotel)) {
                if (is_string($trip->citiesHotel->images)) {
                    $trip->citiesHotel->images = json_decode($trip->citiesHotel->images, true);
                }
                if (is_string($trip->citiesHotel->features)) {
                    $trip->citiesHotel->features = json_decode($trip->citiesHotel->features, true);
                }
                if (is_string($trip->citiesHotel->review)) {
                    $trip->citiesHotel->review = json_decode($trip->citiesHotel->review, true);
                }
            }

            // Decode the tourismPlace fields
            if (isset($trip->publicTripPlace)) {
                foreach ($trip->publicTripPlace as $tripPlace) {
                    if ($tripPlace->tourismPlace) {
                        $decodeTourismPlaceFields($tripPlace->tourismPlace);
                    }
                }
            }

            // Calculate average price of trip points
            $totalPrice = $trip->tripPoint()->sum('price');
            $numberOfTripPoints = $trip->tripPoint()->count();
            $averagePrice = $numberOfTripPoints > 0 ? $totalPrice / $numberOfTripPoints : 0;

            // Add the average price to the trip object
            $trip->averagePrice = $averagePrice;

            return $trip;
        };

        // Fetch the public trip with relationships
        $publicTrip = PublicTrip::where('id', $publicTrip_id)
            ->with('citiesHotel.hotel')
            ->get()->map($mm);

        // Return the response
        return response()->json([
            'publicTrip' => $publicTrip,
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

        $userId = auth()->id();
        $mm = function ($trip) use ($userId) {
            // Calculate average price of trip points
            $totalPrice = $trip->tripPoint()->sum('price');
            $numberOfTripPoints = $trip->tripPoint()->count();
            $averagePrice = $numberOfTripPoints > 0 ? $totalPrice / $numberOfTripPoints : 0;

            // Add the average price to the trip object
            $trip->averagePrice = $averagePrice;

            // Check if the trip is a favorite
            $trip->favorite = Favorite::where('user_id', $userId)
                ->where('publicTrip_id', $trip->id)
                ->exists();

            // Decode specific attributes in cities_hotel
            if (isset($trip->citiesHotel)) {
                if (is_string($trip->citiesHotel->images)) {
                    $trip->citiesHotel->images = json_decode($trip->citiesHotel->images, true);
                }
                if (is_string($trip->citiesHotel->features)) {
                    $trip->citiesHotel->features = json_decode($trip->citiesHotel->features, true);
                }
                if (is_string($trip->citiesHotel->review)) {
                    $trip->citiesHotel->review = json_decode($trip->citiesHotel->review, true);
                }
            }

            // Exclude tripPoint from the trip object
            unset($trip->tripPoint);

            return $trip;
        };
        if ($request->has('classification_id')) {
            $classification = $attrs['classification_id'];

            $theTrips = PublicTrip::whereHas('publicTripClassification', function ($query) use ($classification) {
                $query->where('classification_id', $classification);
            })
                ->with(['citiesHotel', 'citiesHotel.hotel:id,name'])
                ->get()
                ->where('display', true)
                ->map($mm);

            return response()->json([
                'theTrips' => $theTrips,
            ]);
        } else {
            $theTrips = PublicTrip::where('display', true)
                ->with(['citiesHotel', 'citiesHotel.hotel:id,name'])
                ->get()
                ->map($mm);

            return response()->json([
                'theTrips' => $theTrips,
            ]);
        }
    }

    public function displayPublicTrip($publicTrip_id)
    {
        $publicTrip = PublicTrip::find($publicTrip_id);

        if (!$publicTrip) {
            return response([
                'message' => 'publicTrip not found'
            ], 403);
        }

        $publicTrip->display = $publicTrip->display ? false : true;
        $publicTrip->save();
        return response()->json([
            'display' => $publicTrip->display,
        ]);
    }

    //help function:
    private function publicTripSortByMapper()
    {
        $userId = auth()->id();

        return function ($trip) use ($userId) {
            // Calculate average price of trip points
            $totalPrice = $trip->tripPoint()->sum('price');
            $numberOfTripPoints = $trip->tripPoint()->count();
            $averagePrice = $numberOfTripPoints > 0 ? $totalPrice / $numberOfTripPoints : 0;

            // Add the average price to the trip object
            $trip->averagePrice = $averagePrice;

            // Check if the trip is a favorite
            $trip->favorite = Favorite::where('user_id', $userId)
                ->where('publicTrip_id', $trip->id)
                ->exists();

            return $trip;
        };
    }
    public function publicTripSortBy(Request $request)
    {
        $attrs = $request->validate([
            'classification_id' => 'sometimes|integer',
            'sortBy' => 'sometimes|in:Newest,Closet,Price High to Low,Price Low to High',
            'search' => 'sometimes|string'
        ]);

        $userId = auth()->id();
        $mm = function ($trip) use ($userId) {
            // Calculate average price of trip points
            $totalPrice = $trip->tripPoint()->sum('price');
            $numberOfTripPoints = $trip->tripPoint()->count();
            $averagePrice = $numberOfTripPoints > 0 ? $totalPrice / $numberOfTripPoints : 0;

            // Add the average price to the trip object
            $trip->averagePrice = $averagePrice;

            // Check if the trip is a favorite
            $trip->favorite = Favorite::where('user_id', $userId)
                ->where('publicTrip_id', $trip->id)
                ->exists();

            return $trip;
        };

        $sortTrips = function ($trips) use ($attrs) {
            if ($attrs['sortBy'] == 'Newest') {
                $trips = $trips->sortByDesc('created_at');
            } elseif ($attrs['sortBy'] == 'Closet') {
                $trips = $trips->sortBy('dateOfTrip');
            } elseif ($attrs['sortBy'] == 'Price High to Low') {
                $trips = $trips->sortByDesc('averagePrice');
            } elseif ($attrs['sortBy'] == 'Price Low to High') {
                $trips = $trips->sortBy('averagePrice');
            }

            return $trips;
        };

        if ($request->has('classification_id')) {
            $classification = $attrs['classification_id'];

            $theTrips = PublicTrip::where('display', true)->whereHas('publicTripClassification', function ($query) use ($classification) {
                $query->where('classification_id', $classification);});

                if ($request->has('search')) {
                    $theTrips = $theTrips->where('name', 'like', '%' . $attrs['search'] . '%');
                }

                $theTrips=$theTrips->get()->map($this->publicTripSortByMapper());

            if ($request->has('sortBy')) {
                $theTrips = $sortTrips($theTrips)->values();
            }

        } else {
            $theTrips = PublicTrip::where('display', true);
            if ($request->has('search')) {
                $theTrips->where('name', 'like', '%' . $attrs['search'] . '%');
            }
            $theTrips=$theTrips->get()
                ->map($this->publicTripSortByMapper());

            if ($request->has('sortBy')) {
                $theTrips = $sortTrips($theTrips)->values();
            }


        }

        return response()->json([
            'theTrips' => $theTrips,
        ]);
    }

    public function searchPublicTrip($name)
    {
        $theTrips = PublicTrip::where('display', true)
            ->where('name', 'like', '%' . $name . '%')
            ->get()
            ->map($this->publicTripSortByMapper());

        return response()->json([
            'theTrips:' => $theTrips,
        ]);
    }

    /*
    class
        // } elseif ($attrs['sortBy'] == 'Closet') {
        //     $theTrips = PublicTrip::whereHas('publicTripClassification', function ($query) use ($classification) {
        //         $query->where('classification_id', $classification);
        //     })->orderBy('dateOfTrip')->get()->map($mm);


        // } elseif ($attrs['sortBy'] == 'Price High to Low') {
        //     $theTrips = PublicTrip::whereHas('publicTripClassification', function ($query) use ($classification) {
        //         $query->where('classification_id', $classification);
        //     })->get()->map($mm)->sortByDesc('averagePrice');

        // } elseif ($attrs['sortBy'] == 'Price Low to High') {
        //     $theTrips = PublicTrip::whereHas('publicTripClassification', function ($query) use ($classification) {
        //         $query->where('classification_id', $classification);
        //     })->get()->map($mm)->sortBy('averagePrice');
        // }

    not class
            // if ($attrs['sortBy'] == 'Newest') {
            //     $theTrips = PublicTrip::where('display', true)
            //     ->orderBy('created_at', 'desc')
            //     ->get()
            //     ->map($mm);
            // } elseif ($attrs['sortBy'] == 'Closet') {
            //     $theTrips = PublicTrip::where('display', true)
            //     ->orderBy('dateOfTrip')
            //     ->get()
            //     ->map($mm);

            // } elseif ($attrs['sortBy'] == 'Price High to Low') {
            //     $theTrips = PublicTrip::where('display', true)
            // ->get()
            // ->map($mm)->sortByDesc('averagePrice');

            // } elseif ($attrs['sortBy'] == 'Price Low to High') {
            //     $theTrips = PublicTrip::where('display', true)
            // ->get()
            // ->map($mm)->sortBy('averagePrice');
            // }
        // return response()->json([
        //     'theTrips' => $theTrips,
        // ]);
         */
}
