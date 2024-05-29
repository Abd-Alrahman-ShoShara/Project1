<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Models\NormalUser;
use App\Models\User;

class NormalUserController extends Controller
{

    public function updatephone(Request $request){
        $request->validate([
            'phone'=>'required|numeric|unique:normal_users|digits:10',
        ]);
        $user=Auth::user();

        $code = mt_rand(1000, 9999);
        NormalUser::where('user_id',$user->id)->verification_code = $code;

        $AuthController = new AuthController();
        $AuthController->sendCode($request->phone, $code,$user['name']);
        return response([
            'message' => 'we send code to your new phoneNumber. Please enter the verification code.',
            'user_phone' => $request->phone,
        ],200);
    }

    public function verifyNewPhone(Request $request){
        $request->validate([
            'phone'=>'required|numeric|unique:normal_users|digits:10',
            'code' => 'required|numeric',
        ]);

        $normalUser = NormalUser::where('user_id',Auth::user()->id)->first();

        if ($normalUser->verification_code == $request->code) {

            $normalUser->update([
                'phone'=>$request->phone,
            ]);
            return response([
                'message' => 'Verification successful. the phone updated.',

            ],200);
        } else {
            return response([
                'message' => 'Invalid verification code.',
            ], 422);
        }

    }
}
