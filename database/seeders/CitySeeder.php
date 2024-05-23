<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        City::create([
            'name'=>'damascus',
            'country'=> 'syria',
        ]);

        City::create([
            'name'=>'aleppo',
            'country'=> 'syria',
        ]);

        City::create([
            'name'=>'homs',
            'country'=> 'syria',
        ]);

        City::create([
            'name'=>'paris',
            'country'=> 'france',
        ]);

        City::create([
            'name'=>'monaco',
            'country'=> 'france',
        ]);

        City::create([
            'name'=>'marseille',
            'country'=> 'france',
        ]);

        City::create([
            'name'=>'roma',
            'country'=> 'italy',
        ]);

        City::create([
            'name'=>'milan',
            'country'=> 'italy',
        ]);

        City::create([
            'name'=>'genova',
            'country'=> 'italy',
        ]);

    }
}
