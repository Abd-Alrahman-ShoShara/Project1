<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Stichoza\GoogleTranslate\GoogleTranslate;

class CityController extends Controller
{
    public function addCity(Request $request){
        $attr =$request->validate([
            'name'=>'required|unique:cities',
            'country'=>'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,bmp|max:4096',

        ]);
        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('uploads/'), $imageName);
            $imageUrl = URL::asset('uploads/' . $imageName);
        } else {
            $imageUrl = null;
        }
        $city = City::create([
            'name'=>$attr['name'],
            'country'=>$attr['country'],
            'image'=>$imageUrl
        ]);
        return response()->json([
            'message'=> ' the city created successfully',
            'city'=> $city->id,
        ],200);
    }

    public function allCities(){
        $cities = City::all();
        $tr=new GoogleTranslate();
        foreach($cities as $city){
        $name1 = $tr->setTarget('ar')->translate($city->name);
        $country=$tr->setTarget('ar')->translate($city->country);
        $translatedCities[] = [
            'id' => $city->id,
            'name' => $name1,
            'country' => $country
        ];   }
        return response()->json([
            'CityData' => $translatedCities,
        ]);
    }


    public function searchCity($name){
        $theCity= City::where('name','like','%' . $name . '%')
        ->orwhere('country','like','%' . $name . '%')
        ->get();
        return response()->json([
            'the Cities :' => $theCity,
        ]);
    }

    public function deleteCity($city_id){
        $city =City::find($city_id);

        if(!$city){
            return response()->json(['message' => 'city is not found'], 404);
        }
        $city->delete();
        return response()->json(['message' => ' deleted successfully'], 200);
    }
    public function getCityInfo($city_id){
        return response([
            'theCity'=>City::find($city_id),
        ]);
    }
    public function updateCity(Request $request,$city_id){
        $city=City::find($city_id);

        $attr =$request->validate([
            'name'=>'required|unique:cities,name,' .$city->id,
            'country'=>'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,bmp|max:4096',
        ]);
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($city->image && file_exists(public_path('uploads/' . basename($city->image)))) {
                unlink(public_path('uploads/' . basename($city->image)));
            }

            // Upload the new image
            $imageName = time() . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('uploads/'), $imageName);
            $imageUrl = URL::asset('uploads/' . $imageName);
        } else {
            $imageUrl = $city->image;
        }

        $city->update([
            'name'=>$attr['name'],
            'country'=>$attr['country'],
            'image'=>$imageUrl,
        ]);
        return response()->json([
            'message'=> ' the city updated successfully',
            'city'=> $city,
        ],200);
    }
}
