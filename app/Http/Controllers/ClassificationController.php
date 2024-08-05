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
    public function Classifications(){
        $classification=Classification::all();
        return response()->json(['classification'=>$classification]);
    }

    public function deleteClassification($classification_id)
    {
        $classification = Classification::find($classification_id);

        if (!$classification) {
            return response()->json(['message' => 'classification is not found'], 404);
        }
        $classification->delete();
        return response()->json(['message' => ' deleted successfully'], 200);
    }


}
