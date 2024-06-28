<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function register(Request $request)
    {
        // Validate request
//        $validator = Validator::make($request->all(), [
//            'name' => 'required|string|max:255',
//            'email' => 'required|string|email|max:255|unique:users',
//            'password' => 'required|string|min:6|confirmed',
//        ]);

//        if ($validator->fails()) {
//            return response()->json($validator->errors(), 400);
//        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate JWT token
        $token = 'JWTAuth::fromUser($user)';

        return response()->json(compact('user', 'token'), 201);    }

    /**
     * Display a listing of the resource.
     */
//    public function index()
//    {
//        //
//    }

    /**
     * Store a newly created resource in storage.
     */
//    public function store(Request $request)
//    {
//        //
//    }

    /**
     * Display the specified resource.
     */
//    public function show(User $user)
//    {
//        //
//    }

    /**
     * Update the specified resource in storage.
     */
//    public function update(Request $request, User $user)
//    {
//        //
//    }

    /**
     * Remove the specified resource from storage.
     */
//    public function destroy(User $user)
//    {
//        //
//    }
}
