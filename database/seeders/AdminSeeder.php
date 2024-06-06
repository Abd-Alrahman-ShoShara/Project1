<?php

namespace Database\Seeders;

use App\Models\NormalUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser=User::create([
            
            'name'=>'admin',
            'type'=>'normal'
        ]);
        NormalUser::create([
            'user_id'=>$adminUser->id,
            'phone'=>'0983812064',
            'password'=>Hash::make('123456789'),
            'role'=>'admin',
        ]);
        $NormalUser=User::create([
            
            'name'=>'abd',
            'type'=>'normal'
        ]);
        NormalUser::create([
            'user_id'=>$NormalUser->id,
            'phone'=>'0943959774',
            'password'=>Hash::make('123456789'),
            'role'=>'normalUser',
        ]);
    }
}
