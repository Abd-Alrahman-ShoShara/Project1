<?php

namespace App\Http\Controllers;

use App\Models\PublicTrip;
use App\Models\Trip;

class UserTripController extends Controller
{
    public function activeTrips()
{
    $user_id = auth()->user()->id;
    $activePrivateTrips = Trip::where('user_id', $user_id)
                                ->whereDate('dateOfTrip', '>=', now()->startOfDay())
                                ->get();

    $activePublicTrips = PublicTrip::whereHas('tripPoint.userPublicTrip', function ($query) use ($user_id) {
                            $query->where('user_id', $user_id);
                            })->whereDate('dateOfTrip', '>=', now()->startOfDay())
                            ->get();

    $AllActiveTrips = $activePrivateTrips->concat($activePublicTrips);

    return response(['activeTrips' => $AllActiveTrips]);
}



    public function pastTrips(){
    $user_id = auth()->user()->id;
    $pastPrivateTrips = Trip::where('user_id', $user_id)
                                ->whereDate('dateOfTrip', '<', now()->startOfDay())
                                ->get();

    $pastPublicTrips = PublicTrip::whereHas('tripPoint.userPublicTrip', function ($query) use ($user_id) {
                            $query->where('user_id', $user_id);
                            })->whereDate('dateOfTrip', '<', now()->startOfDay())
                            ->get();

    $AllPastTrips = $pastPrivateTrips->concat($pastPublicTrips);

    return response(['pastTrips' => $AllPastTrips]);
}
}
