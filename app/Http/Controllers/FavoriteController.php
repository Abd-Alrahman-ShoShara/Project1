<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\PublicTrip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function faveOrNot($publicTrip_id)
    {
        $publicTrip=PublicTrip::find($publicTrip_id);

        if(!$publicTrip)
        {
            return response([
                'message'=>'publicTrip not found'
            ],403);
        }

        $favorite=favorite::where([['user_id',auth()->user()->id],['publicTrip_id',$publicTrip_id]])->first();


        if(!$favorite)
        {
        Favorite::create([
            'publicTrip_id'=>$publicTrip_id,
            'user_id'=>auth()->user()->id
        ]);

        return response([
            'message'=>'favorite'
        ],200);
        }

        //else unlike
        $favorite->delete();

        return response([
            'message'=>'unfavorite'
        ],200);
    }

    public function favorite()
    {
    return response([
        'your favorite'
            => Favorite::
            where('user_id',auth()->user()->id)->with('publicTrip')
            ->get()
    ], 200);
    }
}
