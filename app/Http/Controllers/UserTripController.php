<?php

namespace App\Http\Controllers;

use App\Models\PublicTrip;
use App\Models\Trip;
use App\Models\UserPublicTrip;
use Illuminate\Support\Facades\Auth;

class UserTripController extends Controller
{
    private function mm() {
        return function ($trip) {
        $name = $trip->toCity->name;
        $image = $trip->toCity->image;
        // Add the average price to the trip object
        $trip->name = $name;
        $trip->image =$image;
        $trip->type = 'private';
        // Check if the trip is a favorite
        return $trip;
    };
}
    public function activeTrips()
    {
        $user_id = auth()->user()->id;
        $activePrivateTrips = Trip::where([['user_id', $user_id], ['state', 'completed']])
            ->whereDate('dateEndOfTrip', '>=', now()->startOfDay())
            ->get()->map($this->mm())->select('id','name','image','dateOfTrip','dateEndOfTrip','type');

        $activePublicTrips = PublicTrip::whereHas('tripPoint.userPublicTrip', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->whereDate('dateEndOfTrip', '>=', now()->startOfDay())
            ->get()->map(function ($trip) {
                $trip->type = 'public';
                return $trip;
            })->select('id','name','image','dateOfTrip','dateEndOfTrip','type');

        $AllActiveTrips = $activePrivateTrips->concat($activePublicTrips)->sortBy('id')->values();

        return response([
            'AllActiveTrips' => $AllActiveTrips,
           // 'activePublicTrips' => $activePublicTrips,
        ]);
    }


    public function pastTrips()
    {
        $user_id = auth()->user()->id;
        $pastPrivateTrips = Trip::where('user_id', $user_id)
            ->whereDate('dateEndOfTrip', '<', now()->startOfDay())
            ->get()->map($this->mm())->select('id','name','image','dateOfTrip','dateEndOfTrip','type');

        $pastPublicTrips = PublicTrip::whereHas('tripPoint.userPublicTrip', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->whereDate('dateEndOfTrip', '<', now()->startOfDay())
            ->get()->map(function ($trip) {
                $trip->type = 'public';
                return $trip;
            })->select('id','name','image','dateOfTrip','dateEndOfTrip','type');

            $AllPastTrips = $pastPrivateTrips->concat($pastPublicTrips)->sortBy('id')->values();
        return response()->json([
            'AllPastTrips' => $AllPastTrips,
           // 'pastPublicTrips' => $pastPublicTrips,
        ]);
    }


    public function userPublicTripBooking($publicTrip_id)
    {
        $userPublicTrip = UserPublicTrip::where([['user_id', Auth::user()->id],['state','completed']])
            ->whereHas('tripPoint.publicTrip', function ($query) use ($publicTrip_id) {
                $query->where('id', $publicTrip_id);
            })->get();

            return response()->json(['userPublicTripBooking:' => $userPublicTrip]);
    }
}
