<?php

namespace App\Http\Controllers;

use App\Models\BookingHotel;
use App\Models\RoomHotel;
use App\Models\Trip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingHotelController extends Controller
{
    public function addBookingHotel(Request $request, $trip_id) {
        // Validate the input
        $validator = Validator::make($request->all(), [
            'rooms' => 'required|array',
            'rooms.*.roomHotel_id' => 'required|integer|exists:room_hotels,id',
            'rooms.*.numberOfRoom' => 'required|integer|min:1',
            'rooms.*.checkIn' => 'required|date',
            'rooms.*.checkOut' => 'required|date|after:rooms.*.checkIn',
        ]);
        $trip = Trip::find($trip_id);
        if(!$trip){
            return response()->json([
                'message'=>'the trip dose not exiset'
            ],404);
        }

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // Retrieve the validated input
        $rooms = $request->input('rooms');
        $bookings = [];
        $totalPrice = 0;
        $numberOfNights = 0;

        foreach ($rooms as $room) {
            $roomHotel = RoomHotel::find($room['roomHotel_id']);
            if (!$roomHotel) {
                return response()->json([
                    'message' => 'Room hotel not found',
                ], 404);
            }

            $start = Carbon::parse($room['checkIn']);
            $end = Carbon::parse($room['checkOut']);
            $numberOfNights = $start->diffInDays($end);
            $roomTotalPrice = $room['numberOfRoom'] * $roomHotel->price * $numberOfNights;

            $bookingHotelRoom = BookingHotel::create([
                'trip_id' => $trip_id,
                'roomHotel_id' => $room['roomHotel_id'],
                'numberOfRoom' => $room['numberOfRoom'],
                'checkIn' => $room['checkIn'],
                'checkOut' => $room['checkOut'],
                'price' => $roomTotalPrice
            ]);

            $totalPrice += $roomTotalPrice;
            $bookings[] = $bookingHotelRoom;
        }

        return response()->json([
            'message' => 'The rooms were booked successfully',
            'bookings' => $bookings,
            'totalPrice' => $totalPrice,
            'numberOfNights' => $numberOfNights,
        ], 200);
    }
}