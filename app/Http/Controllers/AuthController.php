<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hash;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(Request $request){
        $data = $request->validate([
            "name" => "required|string",
            "email" => "required|email",
            "password" => "required|min:8",

        ]);

        $user = User::create([
            "name" => $data["name"],
            "email" => $data["email"],
            "password"=> Hash::make($data["password"]),
        ]);

        return response()->json([
            "message" => "registered sucess",
            "user" => $user,
        ]);
    }

    public function login(Request $request){
         $data = $request->validate([
            "email" => "required|email",
            "password" => "required"
         ]);

        $user = User::where("email", $data["email"])->first();

        if(!$user || !Hash::check($data["password"], $user->password)){
            return response()->json([
                "message"=> "invalid"
            ], 401);
        }

        $session = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            "session" => $session,
            "message"=> "user exist",
           "user" =>$user
        ]);
    }


}
