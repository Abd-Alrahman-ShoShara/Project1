<?php

namespace Database\Seeders;

use App\Models\Airport;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AirportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Airport::create([
            'name'=>'Airport1',
            'city_id'=>1,
        ]);
        Airport::create([
            'name'=>'Airport2',
            'city_id'=>1,
        ]);
        Airport::create([
            'name'=>'Airport3',
            'city_id'=>2,
        ]);
        Airport::create([
            'name'=>'Airport4',
            'city_id'=>3,
        ]);
        Airport::create([
            'name'=>'Airport5',
            'city_id'=>3,
        ]);
        Airport::create([
            'name'=>'Airport6',
            'city_id'=>4,
        ]);
    }
}
