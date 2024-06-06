<?php

namespace Database\Seeders;

use App\Models\CitiesHotel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitiesHotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        CitiesHotel::create([
            'city_id'=>'1',
            'hotel_id'=> '1',
            'description'=>'aaa1',
            'avarageOfPrice'=>'50',
            'features'=>'kkm',
            'review'=>'lllll',
        ]);

        CitiesHotel::create([
            'city_id'=>'2',
            'hotel_id'=> '2',
            'description'=>'aaa2',
            'avarageOfPrice'=>'55',
            'features'=>'kkm',
            'review'=>'lllll',
        ]);

        CitiesHotel::create([
            'city_id'=>'1',
            'hotel_id'=> '3',
            'description'=>'aaa3',
            'avarageOfPrice'=>'60',
            'features'=>'kkm',
            'review'=>'lllll',
        ]);
        CitiesHotel::create([
            'city_id'=>'2',
            'hotel_id'=> '3',
            'description'=>'aaa4',
            'avarageOfPrice'=>'60',
            'features'=>'kkm',
            'review'=>'lllll',
        ]);
        CitiesHotel::create([
            'city_id'=>'2',
            'hotel_id'=> '2',
            'description'=>'aaa5',
            'avarageOfPrice'=>'60',
            'features'=>'kkm',
            'review'=>'lllll',
        ]);



    }
}
