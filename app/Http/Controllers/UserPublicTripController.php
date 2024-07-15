<?php

namespace App\Http\Controllers;

use App\Models\PublicTrip;
use App\Models\TripPoint;
use App\Models\User;
use App\Models\UserPublicTrip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserPublicTripController extends Controller
{
    public function bookingPublicTrip(Request $request)
    {
        $request->validate([
            'tripPoint_id' => 'required|integer',
            'numberOfTickets' => 'required|integer',
            'VIP' => 'required|boolean',
        ]);

        $tripPoint = TripPoint::find($request->tripPoint_id);
        $tripPointPrice = $tripPoint->price;

        $totalPrice = $request->numberOfTickets * $tripPointPrice;

        if ($request->VIP) {
            $totalPrice += 0.3 * $totalPrice;
        }


        $PointBooking = UserPublicTrip::create([
            'user_id' => Auth::user()->id,
            'tripPoint_id' => $request->tripPoint_id,
            'numberOfTickets' => $request->numberOfTickets,
            'price' => $totalPrice,
        ]);

        if ($request->numberOfTickets > $tripPoint->numberOfTickets) {
            return response([
                'the number of ticket you can book:' => $tripPoint->numberOfTickets,
            ], 422);
        }
        TripPoint::where('id', $request->tripPoint_id)
            ->update(['numberOfTickets' => $tripPoint->numberOfTickets - $request->numberOfTickets]);

        return response([
            'message' => 'booking successfully.',
            'theBooking:' => $PointBooking,
        ], 200);
    }


    public function cancelPublicTrip($userPublicTrip_id)
    {

        $cancelledPublicTrip = UserPublicTrip::where('id', $userPublicTrip_id)->first();

        if (!$cancelledPublicTrip) {
            return response()->json([
                'message' => 'User public trip not found.',
            ], 404);
        }
        $tripPoint = TripPoint::find($cancelledPublicTrip->tripPoint_id);

        // Fetch the associated public trip to get the trip date
        $publicTrip = PublicTrip::where('id', $tripPoint->publicTrip_id)->first();

        if (!$publicTrip) {
            return response()->json([
                'message' => 'Associated public trip not found.',
            ], 404);
        }

        $tripDate = new \DateTime($publicTrip->dateOfTrip);
        $currentDate = new \DateTime();
        $interval = $currentDate->diff($tripDate);
        $daysUntilTrip = $interval->days;
        $refundAmount = 0;

        if ($daysUntilTrip > 15) {
            $refundAmount = $cancelledPublicTrip->price;
        } elseif ($daysUntilTrip >= 5 && $daysUntilTrip <= 15) {
            $refundAmount = $cancelledPublicTrip->price * 0.85;
        } else {
            $refundAmount = 0;
        }

        // Process the refund
        $this->processRefund($cancelledPublicTrip->user_id, $refundAmount);

        $cancelledPublicTrip->state = 'cancelled';
        $cancelledPublicTrip->save();

        return response()->json([
            'message' => 'The public trip was cancelled successfully.',
            'refundAmount' => $refundAmount,
            'publicTrip' => $cancelledPublicTrip,
        ], 200);
    }

    private function processRefund($userId, $amount)
    {
        // Fetch the user
        $user = User::find($userId);
        if ($user) {
            $user->wallet += $amount;
            $user->save();
        }
    }
}













  // public function cancelePublicTripe($userPublicTrip_id) {


    //     $cancelledPublicTrip = UserPublicTrip::where('id', $userPublicTrip_id)->first();

    //     if (!$cancelledPublicTrip) {
    //         return response()->json([
    //             'message' => ' User public trip not found.',
    //         ], 404);
    //     }

    //     $cancelledPublicTrip->state='cancelled';
    //     $cancelledPublicTrip->save();

    //     return response()->json([
    //         'message' => 'The public trip was cancelled successfully.',
    //         'publicTrip' => $cancelledPublicTrip,
    //     ], 200);
    // }
