<?php

namespace Database\Seeders;

use App\Models\Classification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Classification::create(['name'=>'Adventure']);
        Classification::create(['name'=>'Cultural/Historical']);
        Classification::create(['name'=>'Nature-based']);
        Classification::create(['name'=>'Relaxation']);
        Classification::create(['name'=>'Learning']);
        Classification::create(['name'=>'Special Interest']);
    }
}
