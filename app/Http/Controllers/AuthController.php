<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Model\ConfirmCode;
use Twilio\Rest\Client;


class AuthController extends Controller
{
    public function adminLogin(AuthRequest $request) {
        $credential = $request->only(['email', 'password']);
        if(Auth::attempt($credential)){
            $user = Auth::user();
            if($user->isAdmin){
                $token = Auth::user()->createToken('access_token');
                return response()->json(['accessToken' => $token->accessToken, 'user' => $user], 200);
            }
        }
        return response()->json('Incorrect email or password', 401);
    }
    public function currentAdmin(AuthRequest $request) {
        $user = $request->user();
        if($user->isAdmin){
            return response()->json($request->user(), 200);
        } else {
            return response()->json('Unauthenticated', 401);
        }
    }
    public function currentUser(AuthRequest $request) {
        $user = $request->user();
        return response()->json($request->user(), 200);
    }
    public function changePassword(AuthRequest $request, $id) {
        $user = User::find($id);
        $password = $request->password;
        if(Hash::check($password, $user->password)){
            $user->password = bcrypt($request->newPassword);
            $user->save();
            return response()->json();
        } else {
            return response()->json('Wrong Password', 401);
        }
    }
    public function userLogin(AuthRequest $request) {
        $credential = $request->only(['phoneNumber', 'password']);
        if(Auth::attempt($credential)){
            $user = Auth::user();
            $token = Auth::user()->createToken('access_token');
            return response()->json(['accessToken' => $token->accessToken, 'user' => $user], 200);
        }
        return response()->json('Incorrect Phone Number or password', 401); 
    }
    public function userSignUp(AuthRequest $request) {
        $phoneNumber = $request->phoneNumber;
        $code = $request->code;
        $confirmCode = ConfirmCode::where('phoneNumber', $phoneNumber)
            ->where('code', $code)
            ->where('confirmable', true)
            ->get();
        if(count($confirmCode) > 0) {
            $user = new User();
            $user->phoneNumber = $phoneNumber;
            $user->name = $request->name;
            $user->isAdmin = false;
            $user->password = bcrypt($request->password);
            $user->save();
            return response()->json();
        } else {
            return response()->json('Invalid Confirm Code', 401);
        }
    }
    public function getConfirmCode(AuthRequest $request) {
        //send sms
        $code = mt_rand(1000, 9999);
        $phoneNumber = $request->phoneNumber;

        $sid    = env( 'TWILIO_SID' );
        $token  = env( 'TWILIO_TOKEN' );
        $client = new Client( $sid, $token );
        $message = 'Your Account Confirm Code is '.$code;
        $client->messages->create(
            $phoneNumber,
            [
                'from' => '+17637102504',
                'body' => $message,
            ]
        );

        $confirmCodes = ConfirmCode::where('phoneNumber', $phoneNumber)->update(['confirmable' => false]);
        $confirmCode = new ConfirmCode();
        $confirmCode->phoneNumber = $phoneNumber;
        $confirmCode->code = $code;
        $confirmCode->confirmable = true;
        $confirmCode->save();
        return response()->json();
    }
}
