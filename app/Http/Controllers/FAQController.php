<?php

namespace App\Http\Controllers;

use App\Models\FAQ;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    public function addQuastion(Request $request){
        $attr =$request->validate([
            'quastion'=>'required|unique:f_a_q_s|string',
            'answer'=>'required',
            'type'=>'nullable|in:Flights,Stays,Activities'

        ]);
       // if($request->type==null)
        $quastionAndAnswer = FAQ::create([
            'quastion'=>$attr['quastion'],
            'answer'=>$attr['answer'],
            'type'=>$request->type,
        ]);
        return response()->json([
            'message'=> ' the quastionAndAnswer created successfully',
            'quastionAndAnswer'=> $quastionAndAnswer,
        ],200);
    }
    public function getQusationInfo($quastion_id)
    {
        return response([
            'quastion' => FAQ::where('id', $quastion_id)->get(),
        ]);
    }


    public function allQuastions(){

        $quastionAndAnswers = FAQ::all();
        return response()->json([
            'quastionAndAnswers' => $quastionAndAnswers,
        ]);
    }

    public function allQuastionsByType(Request $request){
        $attrs=$request->validate([
            'type'=>'required|in:Flights,Activities,Stays'
        ]);
        $quastionAndAnswers = FAQ::where('type',$attrs['type'])->get();
        return response()->json([
            'quastionAndAnswers' => $quastionAndAnswers,
        ]);
    }


    public function searchQuastion($quastion){
        $quastion= FAQ::where('quastion','like','%' . $quastion . '%')
        ->get();
        return response()->json([
            'the quastion :' => $quastion,
        ]);
    }

    public function deleteQuastion($quastion_id){
        $quastion =FAQ::find($quastion_id);

        if(!$quastion){
            return response()->json(['message' => 'quastion is not found'], 404);
        }
        $quastion->delete();
        return response()->json(['message' => ' deleted successfully'], 200);
    }
    public function getQuastionInfo($quastion_id){
        return response([
            'theQuastion'=>FAQ::find($quastion_id),
        ]);
    }
    public function updateQuastion(Request $request,$quastion_id){
        $quastion=FAQ::find($quastion_id);

        $attr =$request->validate([
            'quastion'=>'required|unique:f_a_q_s,quastion,' .$quastion->id,
            'answer'=>'required',
        ]);

        $quastion->update([
            'quastion'=>$attr['quastion'],
            'answer'=>$attr['answer'],
        ]);
        return response()->json([
            'message'=> ' the quastion updated successfully',
            'quastion'=> $quastion,
        ],200);
    }
}
