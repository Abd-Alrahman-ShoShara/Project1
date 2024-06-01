<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function addReview(Request $request){
        $attr = $request->validate([
            'review'=>'required|numeric|between:1,5',
            'comment'=>'required|string',
        ]);
        $comment=Review::create([
            'review'=>$request->review,
            'comment'=>$request->comment,
            'user_id'=>auth()->user()->id,
        ]);
        return response()->json([
            
            'message:'=>'review created successfully',
            'review'=> $comment,
        ],200);


    }
    public function allReview()
    {
        $reviews = Review::with('user:id,name')->get();

        return response()->json([
            'reviews_count' => $reviews->count(),
            'reviews' => $reviews,
        ], 200);
    }
}
