<?php

namespace App\Http\Controllers;

use App\Models\PublicTrip;
use App\Models\Trip;
use App\Models\UserPublicTrip;
use Illuminate\Support\Facades\Auth;

class UserTripController extends Controller
{
    private function mm()
    {
        return function ($trip) {
            $name = $trip->toCity->name;
            $image = $trip->toCity->image;
            // Add the average price to the trip object
            $trip->name = $name;
            $trip->image = $image;
            $trip->type = 'private';
            // Check if the trip is a favorite
            return $trip;
        };
    }
    public function activeTrips()
    {
        $user_id = auth()->user()->id;
        $activePrivateTrips = Trip::where([['user_id', $user_id], ['state', 'completed']])
            ->whereDate('dateOfTrip', '>=', now()->startOfDay())
            ->get()->map($this->mm())->select('id', 'name', 'image', 'dateOfTrip', 'dateEndOfTrip', 'type');

        $activePublicTrips = PublicTrip::whereHas('tripPoint.userPublicTrip', function ($query) use ($user_id) {
            $query->where([['user_id', $user_id], ['state', 'completed']]);
        })->whereDate('dateOfTrip', '>=', now()->startOfDay())
            ->get()->map(function ($trip) {
                $trip->type = 'public';
                return $trip;
            })->select('id', 'name', 'image', 'dateOfTrip', 'dateEndOfTrip', 'type');

        $AllActiveTrips = $activePrivateTrips->concat($activePublicTrips)->sortBy('id')->values();

        return response([
            'AllTrips' => $AllActiveTrips,
            // 'activePublicTrips' => $activePublicTrips,
        ]);
    }


    public function pastTrips()
    {
        $user_id = auth()->user()->id;
        $pastPrivateTrips = Trip::where([['user_id', $user_id], ['state', 'completed']])
            ->whereDate('dateOfTrip', '<', now()->startOfDay())
            ->get()->map($this->mm())->select('id', 'name', 'image', 'dateOfTrip', 'dateEndOfTrip', 'type');

        $pastPublicTrips = PublicTrip::whereHas('tripPoint.userPublicTrip', function ($query) use ($user_id) {
            $query->where([['user_id', $user_id], ['state', 'completed']]);
        })->whereDate('dateOfTrip', '<', now()->startOfDay())
            ->get()->map(function ($trip) {
                $trip->type = 'public';
                return $trip;
            })->select('id', 'name', 'image', 'dateOfTrip', 'dateEndOfTrip', 'type');

        $AllPastTrips = $pastPrivateTrips->concat($pastPublicTrips)->sortBy('id')->values();
        return response()->json([
            'AllTrips' => $AllPastTrips,
            // 'pastPublicTrips' => $pastPublicTrips,
        ]);
    }

    public function userPublicTripBooking($publicTrip_id)
    {
        $userPublicTrip = UserPublicTrip::where([['user_id', Auth::user()->id], ['state', 'completed']])
            ->whereHas('tripPoint.publicTrip', function ($query) use ($publicTrip_id) {
                $query->where('id', $publicTrip_id);
            })->get();

        return response()->json(['userPublicTripBooking:' => $userPublicTrip]);
    }

    public function getCancelledUserPublicTrip($publicTrip_id)
    {
        $userPublicTrip = UserPublicTrip::whereHas('tripPoint', function ($query) use ($publicTrip_id) {
            $query->where('publicTrip_id', $publicTrip_id);
        })->where([
            ['user_id', Auth::user()->id],
            ['state', 'cancelled']
        ])->with('tripPoint.city')->get();

        return response()->json([
            'cancelledUserPublicTrip' => $userPublicTrip,
        ]);
    }

    public function getActiveUserPublicTrip($publicTrip_id)
    {
        $userPublicTrip = UserPublicTrip::whereHas('tripPoint.publicTrip', function ($query) use ($publicTrip_id) {
            $query->where([['id', $publicTrip_id], ['state', 'completed']])->whereDate('dateOfTrip', '>=', now()->startOfDay());
        })->where('user_id', Auth::user()->id)->with('tripPoint.city')->get();

        return response()->json([
            'activeUserPublicTrip' => $userPublicTrip,
        ]);
    }

    public function getPastUserPublicTrip($publicTrip_id)
    {
        $userPublicTrip = UserPublicTrip::whereHas('tripPoint.publicTrip', function ($query) use ($publicTrip_id) {
            $query->where([['id', $publicTrip_id], ['state', 'completed']])->whereDate('dateOfTrip', '<', now()->startOfDay());
        })->where('user_id', Auth::user()->id)->with('tripPoint.city')->get();

        return response()->json([
            'pastUserPublicTrip' => $userPublicTrip,
        ]);
    }
}
