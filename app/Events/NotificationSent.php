<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $user_id;
    public $trip_id;
    public $event;
    public function __construct($message,$user_id,$trip_id,$event)
    {
        $this->message = $message;
        $this->user_id = $user_id;
        $this->trip_id = $trip_id;
        $this->event = $event;
    }


    public function broadcastOn(): array
    {
        return [
            new Channel('popup-channel'),
        ];
    }

    public function broadcastAs()
    {
        return $this->event;
    }

    public function broadcastWith()
    {
        return ['name' => $this->message, 'userId' => $this->user_id,'tripId' => $this->trip_id];
    }
}
