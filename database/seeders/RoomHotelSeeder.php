<?php

namespace Database\Seeders;

use App\Models\RoomHotel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomHotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // SingleRoom','DeluxeRoom','SuiteRoom
        //
        RoomHotel::create([
            'citiesHotel_id'=>1,
            'typeOfRoom'=>'SingleRoom',
            'description'=>'description',
            'numberOfRoom'=>50,
            'price'=>50,
        ]);
        RoomHotel::create([
            'citiesHotel_id'=>1,
            'typeOfRoom'=>'DeluxeRoom',
            'description'=>'description',
            'numberOfRoom'=>70,
            'price'=>75,
        ]);
        RoomHotel::create([
            'citiesHotel_id'=>1,
            'typeOfRoom'=>'SuiteRoom',
            'description'=>'description',
            'numberOfRoom'=>30,
            'price'=>100,
        ]);
        RoomHotel::create([
            'citiesHotel_id'=>2,
            'typeOfRoom'=>'SingleRoom',
            'description'=>'description',
            'numberOfRoom'=>25,
            'price'=>90,
        ]);
        RoomHotel::create([
            'citiesHotel_id'=>2,
            'typeOfRoom'=>'DeluxeRoom',
            'description'=>'description',
            'numberOfRoom'=>30,
            'price'=>150,
        ]);
        RoomHotel::create([
            'citiesHotel_id'=>2,
            'typeOfRoom'=>'SuiteRoom',
            'description'=>'description',
            'numberOfRoom'=>20,
            'price'=>200,
        ]);
    }
}
