<?php

namespace App\Http\Controllers;

use App\Models\Classification;
use Illuminate\Http\Request;

class ClassificationController extends Controller
{
    public function addClassification(Request $request){
        $request->validate(['name'=>'required|string']);
        $classification = Classification::create(['name'=>$request->name]);
        return response()->json(['message'=>'created successfully',$classification]);
    }   
}
