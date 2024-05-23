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
            'checkIn' => 'required|date',
            'checkOut' => 'required|date|after:checkIn',
            'rooms' => 'required|array',
            'rooms.*.roomHotel_id' => 'required|integer|exists:room_hotels,id',
            'rooms.*.numberOfRoom' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $trip = Trip::find($trip_id);
        if (!$trip) {
            return response()->json([
                'message' => 'The trip does not exist'
            ], 404);
        }

        // Retrieve the validated input
        $validated = $validator->validated();
        $rooms = $validated['rooms'];
        $checkIn = $validated['checkIn'];
        $checkOut = $validated['checkOut'];

        $bookings = [];
        $totalPrice = 0;

        $start = Carbon::parse($checkIn);
        $end = Carbon::parse($checkOut);
        $numberOfNights = $start->diffInDays($end);

        foreach ($rooms as $room) {
            $roomHotel = RoomHotel::find($room['roomHotel_id']);
            if (!$roomHotel) {
                return response()->json([
                    'message' => 'Room hotel not found',
                ], 404);
            }

            $roomTotalPrice = $room['numberOfRoom'] * $roomHotel->price * $numberOfNights;

            $bookingHotelRoom = BookingHotel::create([
                'trip_id' => $trip_id,
                'roomHotel_id' => $room['roomHotel_id'],
                'numberOfRoom' => $room['numberOfRoom'],
                'checkIn' => $checkIn,
                'checkOut' => $checkOut,
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