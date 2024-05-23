<?php

namespace App\Listeners;

use App\Models\RoomHotel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateRoomAvailability
{
    /**
     * Create the event listener.
     */
    
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        if ($event->bookingHotel->checkOut < now()) {
            $roomHotel = RoomHotel::find($event->bookingHotel->roomHotel_id);

            // Update the number of rooms if the RoomHotel exists
            if ($roomHotel) {
                $roomHotel->update([
                    'numberOfRoom' => $roomHotel->numberOfRoom + $event->bookingHotel->numberOfRoom,
                ]);
            }
        }
    }
}
