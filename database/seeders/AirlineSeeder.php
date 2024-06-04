<?php

namespace Database\Seeders;

use App\Models\Airline;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AirlineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Airline::create([
            'name' => 'airline1',
            // 'image' => $imageUrl,
        ]);
        Airline::create([
            'name' => 'airline2',
            // 'image' => $imageUrl,
        ]);
        Airline::create([
            'name' => 'airline3',
            // 'image' => $imageUrl,
        ]);
        Airline::create([
            'name' => 'airline4',
            // 'image' => $imageUrl,
        ]);
        Airline::create([
            'name' => 'airline5',
            // 'image' => $imageUrl,
        ]);
   
    }
}
