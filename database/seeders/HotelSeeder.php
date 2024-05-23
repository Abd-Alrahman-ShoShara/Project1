<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //damascus/syria
        Hotel::create([
            'name'=>'City of Jasmine Hotel',
            'rate'=> '4',
        ]);
        Hotel::create([
            'name'=>'Golden Mazzeh Hotel',
            'rate'=> '4',
        ]);
        Hotel::create([
            'name'=>'Dama Rose Hotel',
            'rate'=> '4',
        ]);

        //aleppo/syria
        Hotel::create([
            'name'=>'Sheraton Aleppo Hotel',
            'rate'=> '4',
        ]);
        Hotel::create([
            'name'=>'Arman Hotel',
            'rate'=> '4',
        ]);
        Hotel::create([
            'name'=>'Baron Hotel',
            'rate'=> '4',
        ]);

        //homs/syria
        Hotel::create([
            'name'=>'Safir Homs Hotel',
            'rate'=> '4',
        ]);
        Hotel::create([
            'name'=>'Homs Grand Hotel',
            'rate'=> '3',
        ]);
        Hotel::create([
            'name'=>'Louis Inn Hotel and Restaurant',
            'rate'=> '4',
        ]);

        ///////////////////////////////////////////////////////////////////////////
        //paris/france
        Hotel::create([
            'name'=>'Paris France Hotel',
            'rate'=> '4',
        ]);
        Hotel::create([
            'name'=>'hotelF1 Paris Porte de Châtillon',
            'rate'=> '3',
        ]);
        Hotel::create([
            'name'=>'La Réserve',
            'rate'=> '4',
        ]);

        //monaco/france
        Hotel::create([
            'name'=>'Hotel de Paris Monte-Carlo',
            'rate'=> '4',
        ]);
        Hotel::create([
            'name'=>'Fairmont Monte Carlo',
            'rate'=> '4',
        ]);
        Hotel::create([
            'name'=>'Novotel Monte Carlo',
            'rate'=> '4',
        ]);

        //marseille/france
        Hotel::create([
            'name'=>'Hotel Sofitel Marseille Vieux Port',
            'rate'=> '4',
        ]);
        Hotel::create([
            'name'=>'ibis budget Marseille Vieux-Port',
            'rate'=> '3',
        ]);
        Hotel::create([
            'name'=>'Radisson Blu Hotel, Marseille Vieux Port',
            'rate'=> '4',
        ]);

        //////////////////////////////////////////////////////////////////
        //italy/roma
        Hotel::create([
            'name'=>'Hotel Roma Tor Vergata',
            'rate'=> '3',
        ]);
        Hotel::create([
            'name'=>'Hotel NH Collection Roma Centro',
            'rate'=> '4',
        ]);
        Hotel::create([
            'name'=>'iQ Hotel Roma',
            'rate'=> '4',
        ]);

        //italy/milan
        Hotel::create([
            'name'=>'Hotel Da Vinci',
            'rate'=> '4',
        ]);
        Hotel::create([
            'name'=>'Idea Hotel Milano San Siro',
            'rate'=> '3',
        ]);
        Hotel::create([
            'name'=>'Senato Hotel Milano',
            'rate'=> '4',
        ]);

        //italy/genova
        Hotel::create([
            'name'=>'Hotel Nologo',
            'rate'=> '4',
        ]);
        Hotel::create([
            'name'=>'B&B Hotel Genova',
            'rate'=> '4',
        ]);
        Hotel::create([
            'name'=>'Hotel Palazzo Grillo',
            'rate'=> '4',
        ]);

    }
}
