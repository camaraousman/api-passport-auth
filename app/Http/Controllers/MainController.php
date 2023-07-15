<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class MainController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|string|email||unique:users',
            'password'=>'required|string|confirmed'
        ]);

        $user = new User([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);


        if($user){
            $user->save();
            return response()->json(['status' => true], 201);
        }

        return response()->json(['status' => false]);
    }

    public function login(Request $request){
        $request->validate([
            'email'=>'required|string|email',
            'remember_me'=>'boolean'
        ]);

        $userCredentials = request(['email', 'password']);

        if (!Auth::attempt($userCredentials)){
            return response()->json(['message' => 'Unauthorize'], 401);
        }

        $user = $request->user();
        $tokenResult = $user->createToken('User Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me){
            $token->expires_at = Carbon::now()->addWeeks(3);
        }
        $token->save();

        return response()->json([
            'message' => 'success',
            'access_token'=> $tokenResult->accessToken,
            'token_type'=>'Bearer',
            'expires_at'=>Carbon::parse($tokenResult->token->expires_at)->toDateString()
        ]);
    }

    public function logout(Request $request){
        $request->user()->token()->revoke();

        return response()->json(['message' => 'logout successful']);
    }

    public function profile(Request $request){
        return response()->json($request->user());
    }
}
