<?php

namespace App\Http\Controllers;

use App\Models\CitiesHotel;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class CitiesHotelController extends Controller
{
    public function addCitiesHotel(Request $request){
        $attr =$request->validate([
            'city_id'=>'required',
            'hotel_id'=>'required',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,bmp|max:4096',
            'description'=>'required',
            'features'=>'required',
            'avarageOfPrice'=>'required',
            'review'=>'required',
        ]);
        
        $imageUrls=[];
        if ($request->hasFile('images')) {

               foreach($request->images as $key => $value){

            $imageName = time().$key.'.'.$value->extension();
            $value->move(public_path('uploads/'), $imageName);
            $url = URL::asset('uploads/'.$imageName);
            $imageUrls[] = $url;
           }
        }
        else{
            $imageUrls = null;
        }

        $hotel = CitiesHotel::create([
            
            'city_id'=>$attr['city_id'],
            'hotel_id'=>$attr['hotel_id'],
            'description'=>$attr['description'],
            'features'=>$attr['features'],
            'review'=>$attr['review'],
            'avarageOfPrice'=>$attr['avarageOfPrice'],
            'images'=>json_encode( $imageUrls),

        ]);
        return response()->json([
            'message'=> ' the hotel created successfully',
            'hotel'=> $hotel,
        ],200);
    } 
}
