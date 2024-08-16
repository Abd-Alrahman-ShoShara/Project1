<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use App\Models\Favorite;
use App\Models\PublicTrip;
use App\Models\publicTripClassification;
use App\Models\PublicTripPlace;
use App\Models\Trip;
use App\Models\TripPoint;
use App\Models\User;
use App\Models\UserPublicTrip;
use Carbon\Carbon;
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
        if($request->activities){
        foreach ($request->activities as $activitie) {
            $publicTripPlace = PublicTripPlace::create([
                'tourismPlaces_id' => $activitie,
                'publicTrip_id' => $publicTrip->id,
            ]);
            $publicTripPlaces[] = $publicTripPlace;
        }}


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
        $dateOfTrip=$publicTrip->dateOfTrip;
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

        if($dateOfTrip < $attr['dateOfTrip']){
            $tripPoints = TripPoint::where('publicTrip_id', $publicTrip_id)->get();
            if (!$tripPoints->isEmpty()) {
                foreach ($tripPoints as $tripPoint) {
                    $userPublicTrips = UserPublicTrip::where('tripPoint_id', $tripPoint->id)->get();
                    foreach ($userPublicTrips as $userPublicTrip) {
                        $user = User::find($userPublicTrip->user_id);
                        //notification............
                        NotificationController::sendNotification(
                            'The trip (' . $publicTrip->name . ') has been updated. You can go cancel it',
                            $user->id,$publicTrip_id,'updatePublicTrip');

                    }
                }
            }
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
    public function restoreMoneyPublic($userPublicTrip_id)
    {
        $userPublicTrip = UserPublicTrip::where('id', $userPublicTrip_id)->first();
        $tripPoint=TripPoint::find($userPublicTrip->tripPoint_id);
       // $trip=PublicTrip::find($tripPoint->publicTrip_id);
        if (!$userPublicTrip) {
            return response()->json([
                'message' => 'User public trip not found.',
            ], 404);
        }
        $user = User::find($userPublicTrip->user_id);
        $user->wallet += $userPublicTrip->price;
        $user->points += $userPublicTrip->price * 0.1;
        $user->save();
        $userPublicTrip->state='cancelled';
        $userPublicTrip->save();

        NotificationController::sendNotification($userPublicTrip->price.'$ has been added to your wallet and '.$userPublicTrip->price * 0.1.'points has been added to your points'
        ,$user->id, $tripPoint->publicTrip_id,'restore_money_publicTrip');
        return response()->json([
            'message' => 'The public trip was cancelled successfully.',
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
        $publicTrip = PublicTrip::find($publicTrip_id);
        if (!$publicTrip) {
            return response()->json([
                'message' => 'trip not found.'
            ], 404);
        }

        $tripPoints = TripPoint::where('publicTrip_id', $publicTrip_id)->get();
        if (!$tripPoints->isEmpty()) {
            foreach ($tripPoints as $tripPoint) {
                $userPublicTrips = UserPublicTrip::where('tripPoint_id', $tripPoint->id)->get();
                foreach ($userPublicTrips as $userPublicTrip) {
                    $user = User::find($userPublicTrip->user_id);
                    $user->wallet += $userPublicTrip->price;
                    $user->points += $userPublicTrip->price * 0.1;
                    $user->save();
                    //notification............
                    $message='the trip ('.$publicTrip->name.')has been cancelled, '.
                    $userPublicTrip->price.'$ has been added to your wallet and You have been compensated with '.
                    $userPublicTrip->price * 0.1 .' points as an expression of our regret';
                    NotificationController::sendNotification($message,$user->id,$publicTrip_id,'deletePublicTrip');
                }
            }
        }
        PublicTrip::where('id', $publicTrip_id)->delete();
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
        $publicTrip = PublicTrip::where('id',$publicTrip_id)
        ->with('citiesHotel.hotel')
            ->get()->map($mm);

        // Return the response
        return response()->json([
            'publicTrip' => $publicTrip,
        ]);
    }
    public function getPublicTripInfoWeb($publicTrip_id)
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
        $publicTrip = PublicTrip::find( $publicTrip_id);
        $publicTrip->with('citiesHotel.hotel')
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
        return response()->json([
            'TripPoint' => TripPoint::where('id', $point_id)->with('city')->first()
        ]);
    }

    public function deletePoint($point_id)
    {
        $point = TripPoint::find($point_id);
        $publicTrip=PublicTrip::find($point->publicTrip_id);
        $userPublicTrips = UserPublicTrip::where([['tripPoint_id', $point_id], ['state', 'completed']])->get();

        if (!$point) {
            return response()->json(['message' => 'point is not found'], 404);
        }
        foreach ($userPublicTrips as $userPublicTrip) {
            $user = User::find($userPublicTrip->user_id);
            $user->wallet += $userPublicTrip->price;
            $user->points += $userPublicTrip->price * 0.1;
            $user->save();
            //notification............

            $message='the trip ('.$publicTrip->name.')has been cancelled From your area,'.
                    $userPublicTrip->price.'$ has been added to your wallet and You have been compensated with '.
                    $userPublicTrip->price * 0.1 .' points as an expression of our regret';
                    NotificationController::sendNotification($message,$user->id,$publicTrip->id,'deletePublicTripPoint');

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
        $userId = auth()->id();
        $theDate = Carbon::now()->addDay()->addDay();
                
        $publicTrips = PublicTrip::whereDate('dateOfTrip', $theDate)
        ->whereHas('tripPoint', function ($query) use ($userId) {
            $query->whereHas('userPublicTrip', function ($query) use ($userId) {
                $query->where([['state','completed'],['user_id', $userId]]);
            });
        })->get();

        $userPrivateTrips=Trip::whereDate('dateOfTrip', $theDate)->where([['user_id',$userId],['state','completed']])->get();
        if(!$publicTrips->isEmpty()){
            foreach($publicTrips as $publicTrip){
                NotificationController::sendNotification('your trip '.$publicTrip->name .' is tomorrow ',$userId,$publicTrip->id,'tomorrowPublicTrip');
            }}
        if(!$userPrivateTrips->isEmpty()){
            foreach($userPrivateTrips as $userPrivateTrip){
                NotificationController::sendNotification(' your Private Trip is tomorrow ',$userId,$userPrivateTrip->id,'tomorrowPrivateTrip');
            }
        }
        $attrs = $request->validate([
            'classification_id' => 'sometimes|integer',
        ]);

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
                $query->where('classification_id', $classification);
            });

            if ($request->has('search')) {
                $theTrips = $theTrips->where('name', 'like', '%' . $attrs['search'] . '%');
            }

            $theTrips = $theTrips->get()->map($this->publicTripSortByMapper());

            if ($request->has('sortBy')) {
                $theTrips = $sortTrips($theTrips)->values();
            }
        } else {
            $theTrips = PublicTrip::where('display', true);
            if ($request->has('search')) {
                $theTrips->where('name', 'like', '%' . $attrs['search'] . '%');
            }
            $theTrips = $theTrips->get()
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


    // public function addPublicTripDiscount($publicTrip_id,Request $request){
    //     $publicTrip = PublicTrip::find($publicTrip_id);
    //     $request->validate([
    //         'discount'=>'required|integer|between:0,100'
    //     ]);
    //     $publicTrip->discountType=$request->discount;
    //     $publicTrip->save();
    //     return response()->json([
    //         'message' => 'the discount added successfully',
    //     ]);
    // }

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

         public function gg(){
            echo('snmdvhck');
         }
}
