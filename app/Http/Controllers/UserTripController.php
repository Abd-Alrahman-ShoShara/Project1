<?php

namespace App\Http\Controllers;

use App\Models\PublicTrip;
use App\Models\Trip;
use App\Models\UserPublicTrip;
use Illuminate\Support\Facades\Auth;

class UserTripController extends Controller
{
    public function activeTrips()
    {
        $user_id = auth()->user()->id;
        $activePrivateTrips = Trip::where([['user_id', $user_id], ['state', 'completed']])
            ->whereDate('dateOfTrip', '>=', now()->startOfDay())
            ->get();

        $activePublicTrips = PublicTrip::whereHas('tripPoint.userPublicTrip', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->whereDate('dateOfTrip', '>=', now()->startOfDay())
            ->get();

        $AllActiveTrips = $activePrivateTrips->concat($activePublicTrips);

        return response([
            'activePrivateTrips' => $activePrivateTrips,
            'activePublicTrips' => $activePublicTrips,
        ]);
    }


    public function pastTrips()
    {
        $user_id = auth()->user()->id;
        $pastPrivateTrips = Trip::where('user_id', $user_id)
            ->whereDate('dateOfTrip', '<', now()->startOfDay())
            ->get();

        $pastPublicTrips = PublicTrip::whereHas('tripPoint.userPublicTrip', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
        })->whereDate('dateOfTrip', '<', now()->startOfDay())
            ->get();

        //$AllPastTrips = $pastPrivateTrips->concat($pastPublicTrips);

        return response()->json([
            'pastPrivateTrips' => $pastPrivateTrips,
            'pastPublicTrips' => $pastPublicTrips,
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
