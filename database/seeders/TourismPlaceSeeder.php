<?php

namespace Database\Seeders;

use App\Models\TourismPlace;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TourismPlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 'Sports','Entertainment','Culitural','Natural','Relaxation','Restaurants','Historical','Shopping'
        TourismPlace::create([
            'name' => 'TourismPlace1',
            'description' =>'ttt1',
            'openingHours' => '8 -> 8',
            'recommendedTime' => '4 to 6',
            'type' => 'Sports',
            'city_id' => 1,
        ]);
        TourismPlace::create([
            'name' => 'TourismPlace2',
            'description' =>'ttt2',
            'openingHours' => '8 -> 8',
            'recommendedTime' => '4 to 6',
            'type' => 'Sports',
            'city_id' => 2,
        ]);
        TourismPlace::create([
            'name' => 'TourismPlace3',
            'description' =>'ttt3',
            'openingHours' => '8 -> 8',
            'recommendedTime' => '4 to 6',
            'type' => 'Entertainment',
            'city_id' => 2,
        ]);
        TourismPlace::create([
            'name' => 'TourismPlace4',
            'description' =>'ttt4',
            'openingHours' => '8 -> 8',
            'recommendedTime' => '4 to 6',
            'type' => 'Natural',
            'city_id' => 3,
        ]);
        TourismPlace::create([
            'name' => 'TourismPlace5',
            'description' =>'ttt5',
            'openingHours' => '8 -> 8',
            'recommendedTime' => '4 to 6',
            'type' => 'Natural',
            'city_id' => 3,
        ]);
        TourismPlace::create([
            'name' => 'TourismPlace6',
            'description' =>'ttt6',
            'openingHours' => '8 -> 8',
            'recommendedTime' => '4 to 6',
            'type' => 'Restaurants',
            'city_id' => 2,
        ]);
        TourismPlace::create([
            'name' => 'TourismPlace7',
            'description' =>'ttt7',
            'openingHours' => '8 -> 8',
            'recommendedTime' => '4 to 6',
            'type' => 'Restaurants',
            'city_id' => 1,
        ]);
        TourismPlace::create([
            'name' => 'TourismPlace8',
            'description' =>'ttt8',
            'openingHours' => '8 -> 8',
            'recommendedTime' => '4 to 6',

            'city_id' => 3,
        ]);
        TourismPlace::create([
            'name' => 'TourismPlace9',
            'description' =>'ttt9',
            'openingHours' => '8 -> 8',
            'recommendedTime' => '4 to 6',
            'type' => 'Historical',
            'city_id' => 1,
        ]);
        TourismPlace::create([
            'name' => 'TourismPlace10',
            'description' =>'ttt10',
            'openingHours' => '8 -> 8',
            'recommendedTime' => '4 to 6',
            'type' => 'Shopping',
            'city_id' => 4,
        ]);
    }
}
