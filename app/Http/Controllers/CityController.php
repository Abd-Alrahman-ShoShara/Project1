<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function addCity(Request $request){
        $attr =$request->validate([
            'name'=>'required|unique:cities',
            'country'=>'required',

        ]);
        $city = City::create([
            'name'=>$attr['name'],
            'country'=>$attr['country'],
        ]);
        return response()->json([
            'message'=> ' the city created successfully',
            'city'=> $city->id,
        ],200);
    } 
}
