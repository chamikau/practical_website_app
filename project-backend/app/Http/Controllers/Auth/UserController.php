<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function registerAndSubscribe(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'website_ids' => 'required|array|min:1',
            'website_ids.*' => 'exists:websites,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $subscriber = Subscriber::firstOrCreate(
            ['email' => $user->email],
            ['name' => $user->name]
        );

        $subscriber->websites()->syncWithoutDetaching($request->website_ids);

        return response()->json([
            'message' => 'User registered and subscribed successfully',
            'user' => $user,
        ], 201);
    }

}
