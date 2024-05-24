<?php

namespace Database\Factories;

use App\Models\Airline;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $airline = Airline::inRandomOrder()->first();
        $airlineId = $airline->id;
        $hours = mt_rand(1, 6); 
        $minutes = mt_rand(1, 59);
        $price = $hours > 3 ? mt_rand( 150, 500) : mt_rand( 50, 125);    
        $duration = sprintf('%02d:%02d', $hours, $minutes); 
        $numberOfTickets = mt_rand(1,30);
        
        
        return [
          'airLine_id'=> $airlineId,
          'timeOfTicket'=>$this ->faker->time('H:i'),
          'duration'=>$duration,
          'price'=>$price,
          'numOfTickets'=>$numberOfTickets,
        ];
    }
}
