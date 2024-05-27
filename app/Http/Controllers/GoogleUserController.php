<?php

namespace App\Http\Controllers;

use App\Models\GoogleUser;
use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class GoogleUserController extends Controller
{
    public function googleRegister(Request $request){
        $atter = $request->validate([
            'name'=> 'required|max:255',
            'email'=> 'required|email|unique:google_users,email',
            'google_id'=>'required|unique:google_users',
        ]);
        
        $user=User::create([
            'name'=>$request->name,
            'type'=>'google',          
        ]);

        $googleUser=GoogleUser::create([
            'user_id'=>$user->id,
            'email'=>$request->email,
            'google_id'=>$request->google_id,
        ]);
        if(!$googleUser){
            return response()->json([
                'message'=>'The provided credentials are incorrect'
            ],404);
        }
        $token=$user->createToken('auth_token')->accessToken;

        return response([
            'message'=>'register successfully',
            'token'=>$token
        ],200);
    }
}
