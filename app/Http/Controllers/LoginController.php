<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request) {
        $validatedData = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);
        $user = User::Where('email',$validatedData['email'])->first();
        if(!$user || !Hash::check($validatedData['password'],$user->password)) {
            return response()->json(["message" => 'Invalid User']);
        }
        $token = $user->createToken('auth_token')->accessToken;
        return response()->json(['User' => new UserResource($user),'Access Token'=> $token]);
    }
}
