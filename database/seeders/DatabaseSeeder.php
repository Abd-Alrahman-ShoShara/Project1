<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Airport;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);


        $this->call(CitySeeder::class);
        $this->call(HotelSeeder::class);
        $this->call(CitiesHotelSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(ClassificationSeeder::class);
        $this->call(AirlineSeeder::class);
        $this->call(AirportSeeder::class);
        $this->call(RoomHotelSeeder::class);
       $this->call(TourismPlaceSeeder::class);
    }
}
