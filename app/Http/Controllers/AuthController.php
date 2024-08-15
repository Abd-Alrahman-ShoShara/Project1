<?php

namespace App\Http\Controllers;

use App\Models\NormalUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use UltraMsg\WhatsAppApi;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|numeric|unique:normal_users|digits:10',
            'password' => 'required|min:6|confirmed',
        ]);


        $user = User::create([
            'name' => $request->name,
            'type' => 'normal',
        ]);

        $normalUser = NormalUser::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
        ]);


        $code = mt_rand(1000, 9999);
        $normalUser->verification_code = $code;
        $normalUser->save();

        $this->sendCode($normalUser['phone'], $code, $user['name']);


        return response([
            'message' => 'User registered successfully. Please enter the verification code.',
            'user_id' => $user->id,

        ], 200);
    }
    /////////////////////////////////////////////
    function verifyCode(Request $request)
    {

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'code' => 'required|numeric',
        ]);

        $user = User::findOrFail($request->user_id);
        $normalUser = NormalUser::where('user_id', $request->user_id)->first();

        if (!$normalUser) {
            return response()->json([
                'message' => 'the user not found',
            ], 404);
        }

        if ($normalUser->verification_code == $request->code) {
            $normalUser->is_verified = true;
            $normalUser->save();
            $token = $user->createToken('auth_token')->accessToken;
            return response([
                'message' => 'Verification successful. User is now verified.',
                'token' => $token,
            ], 200);
        } else {
            return response([
                'message' => 'Invalid verification code.',
            ], 422);
        }
    }

    public function login(Request $request)
    {

        $request->validate([
            'phone' => 'required|numeric|digits:10',
            'password' => 'required|min:6',
        ]);

        $normalUser = NormalUser::where('phone', $request->phone)->first();

        $user_id = $normalUser->user_id;
        $user = User::findOrFail($user_id);

        // $normalUser = NormalUser::where('user_id',$request->user_id);


        if (!$normalUser || !Hash::check($request->password, $normalUser->password)) {
            return response([
                'message' => 'The provided credentials are incorrect'
            ], 422);
        }

        $token = $user->createToken('auth_token')->accessToken;


        return response([
            'token' => $token,
        ], 200);
    }


    public function forgetPassword(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric|digits:10',
        ]);

        $normalUser = NormalUser::where('phone', $request->phone)->first();

        if (!$normalUser) {
            return response()->json([
                'message' => 'the phone number is wrong'
            ], 404);
        }
        $code = mt_rand(1000, 9999);
        $normalUser->verification_code = $code;
        $normalUser->save();
        $user_id = $normalUser->user_id;
        $user = User::findOrFail($user_id);


        $this->sendCode($normalUser['phone'], $code, $user['name']);

        return response([
            'message' => 'The code was sent. Please enter it to verification.',
            'user_id' => $user->id,

        ], 200);
    }

    public function verifyForgetPassword(Request $request)
    {

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'code' => 'required|numeric',
        ]);

        // $user = User::findOrFail($request->user_id);

        $normalUser = NormalUser::where('user_id', $request->user_id)->first();

        if ($normalUser->verification_code == $request->code) {

            //  $token=$user->createToken('auth_token')->accessToken;

            return response([
                'message' => 'Verification successful. enter the new password.',

            ], 200);
        } else {
            return response([
                'message' => 'Invalid verification code.',
            ], 422);
        }
    }
    public function resatPassword(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'password' => 'required|min:6|confirmed',
        ]);
        $user = User::findOrFail($request->user_id);
        $normalUser = NormalUser::where('user_id', $request->user_id)->first();

        $normalUser->update(['password' => Hash::make($request['password'])]);

        $token = $user->createToken('auth_token')->accessToken;

        return response()->json([
            'message' => 'the password is updated',
            'token' => $token,
        ], 200);
    }
    public function resatPasswordEnternal(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6',
            'NewPassword' => 'required|min:6|confirmed',
        ]);
        $user_id = auth()->user()->id;
        $normalUser = NormalUser::where('user_id', $user_id)->first();


        if (Hash::check($request->password, $normalUser->password)) {
            $normalUser->update(['password' => Hash::make($request['NewPassword'])]);
            return response()->json([
                'message' => 'the password is updated',
            ], 200);
        }
        return response()->json([
            'message' => 'the old password is wrong',
        ], 422);
    }
    public function logout()
    {
        User::find(Auth::id())->tokens()->delete();
        return response([
            'message' => 'Logged out sucesfully'
        ], 200);
    }







    public function sendCode($phoneNumber, $code, $name)
    {
        require_once(base_path('vendor/autoload.php'));
        $ultramsg_token = env('WHATSAPP_TOKEN');
        $instance_id = env('WHATSAPP_ID');
        $client = new WhatsAppApi($ultramsg_token, $instance_id);
        $number = "+963" . substr($phoneNumber, 1, 9);
        $to = $number;

        $body = 'Hi ' . $name . ', your verification code is: ' . $code;
        $client->sendChatMessage($to, $body);
        // return $this->success(null, 'we send the code');
    }





    /////////////////////////////////////////////////////////////////////////


    public function updateName(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);
        $user = Auth::user()->update([
            'name' => $request->name,
        ]);
        return response([
            'message' => 'updated successfully',
            'user' => $user
        ], 200);
    }


    public function deleteAccount()
    {
        $user = Auth::user();
        $user->delete();

        return response()->json(['message' => 'Account deleted successfully'], 200);
    }


    public function userInfo(){
        return response()->json([
            'user:'=>User::where('id',Auth::id())->with('googleUser','normalUser')->first(),
        ]);
    }
    public function choseLanguage(Request $request) {
        $request->validate([
            'language' => 'required|in:en,ar',
        ]);

        $user = Auth::user();
        $user->language = $request->language;
        $user->save();

        return response()->json([
            'theUser' => $user,
        ]);
    }

}
