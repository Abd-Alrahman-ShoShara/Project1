<?php

namespace App\Http\Controllers;

use App\Models\Attraction;
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

        $user = Auth::user();

        $tripPoint = TripPoint::find($request->tripPoint_id);

        if ($request->numberOfTickets > $tripPoint->numberOfTickets) {
            return response([
                'the number of ticket you can book:' => $tripPoint->numberOfTickets,
            ], 422);
        }

        $tripPointPrice = $tripPoint->price;

        $totalPrice = $request->numberOfTickets * $tripPointPrice;

        if ($request->VIP) {
            $totalPrice += 0.3 * $totalPrice;
        }
        $price=$totalPrice;
        $publicTrip_id = $tripPoint->publicTrip_id;

        $attractionPoint = Attraction::where([
                ['publicTrip_id', $publicTrip_id],
                ['type', 'Points Discount'],
                ['display', true]
            ])->first();

        $attractionTicket = Attraction::where([
                ['publicTrip_id', $publicTrip_id],
                ['type', 'Discount On The Ticket'],
                ['display', true]
            ])->first();

        if ($attractionPoint) {
            $request->validate([
                'pointsOrNot' => 'required|boolean',
            ]);
            if ($request->pointsOrNot) {
                if ($attractionPoint->discount_points > $user->points) {
                    return response()->json([
                        'meesage' => 'your points dose not enough, you need more points '
                    ]);
                } else {
                    $user->points -= $attractionPoint->discount_points;
                    $user->save();
                    NotificationController::sendNotification($attractionPoint->discount_points.'points has been deducted from your points',$user->id,$publicTrip_id,'public-deductedPoints');
                    $totalPrice -= $totalPrice * $attractionPoint->discount / 100;
                }
            }
        } elseif($attractionTicket){

           // $discount = $tripPoint->publicTrip->discountType;
            $totalPrice -= $totalPrice * $attractionTicket->discount / 100;
        }

        if($user->wallet < $totalPrice){
            return response([
                'message'=>"you don't have enough money"
            ],422);
        }

        $PointBooking = UserPublicTrip::create([
            'user_id' => $user->id,
            'tripPoint_id' => $request->tripPoint_id,
            'numberOfTickets' => $request->numberOfTickets,
            'price' => $totalPrice,
        ]);

        TripPoint::where('id', $request->tripPoint_id)
            ->update(['numberOfTickets' => $tripPoint->numberOfTickets - $request->numberOfTickets]);

            $user->wallet-=$totalPrice;
            $user->points+=$totalPrice*0.1;
            $user->save();

            NotificationController::sendNotification($totalPrice*0.1.'points has been added to your points',$user->id,$publicTrip_id,'add-points');
            NotificationController::sendNotification($totalPrice.'$ has been deducted from your wallet',$user->id,$publicTrip_id,'public-deductedWallet');

        return response([
            'message' => 'booking successfully.',
            'theBooking' => $PointBooking,
            'Price before discount if you have a discount'=>$price
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
        $this->processRefund($cancelledPublicTrip->user_id, $refundAmount,$publicTrip->id);

        $cancelledPublicTrip->state = 'cancelled';
        $cancelledPublicTrip->save();

        return response()->json([
            'message' => 'The public trip was cancelled successfully.',
            'refundAmount' => $refundAmount,
            'publicTrip' => $cancelledPublicTrip,
        ], 200);
    }

    private function processRefund($userId, $amount,$publicTrip_id)
    {
        // Fetch the user
        $user = User::find($userId);
        if ($user) {
            $user->wallet += $amount;
            $user->save();

            NotificationController::sendNotification( 'your trip canceled, '.$amount.'$ has been added to your wallet',$user->id,$publicTrip_id,'canceledPuplicTrip');
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
