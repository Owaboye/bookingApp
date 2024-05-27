<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
// use Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct(){
        //$this->middleware("auth:api", ['except' => ['login', 'register']]);
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()->toJson()], 400);
        }

        $user = User::create([
            'name'=> $request->name,
            'email'=> $request->email,
            'password' => Hash::make($request->input('password')),
            ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user'=> $user,
        ], 201);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json(['errors' => $validator->errors()->toJson()], 400);
        }

        $token = Auth::attempt( [
            'email'=> $request->email,
            'password' => $request->password,
        ]);

        if(!$token){
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();

        return response()->json([
            'message'=> 'Logged in successfully',
            'user' => $user,
            'authorization' => [
                'access_token' => $token,
                'token_type' => 'bearer',
             ]
        ]);
    }

    public function profile(){
        return response()->json([
            Auth::user()
        ]);
    }

    public function logout(){
        Auth::logout();
        return   response()->json([
            'message' => 'user logged out successfully'
        ]);
    }

    public function unauthorize_access(Request $request){
        $user = Auth::user();

        if(!$user){
            return response()->json(['Message' => 'Unauthorize access'], 400);
        }
    }
}
