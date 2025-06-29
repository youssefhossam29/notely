<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Resources\UserResource as UserResource;
use Illuminate\Support\Facades\Hash;

class GoogleAuthController extends Controller
{
    public function callBack(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string',
        ]);

        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->userFromToken($request->access_token);
        } catch (\Exception $e) {
            return apiResponse([], "Unable to login with your Google account!", 401);
        }

        $user = User::where('email', $googleUser->email)->first();
        if ($user) {
            if (!$user->google_id) {
                $user->google_id = $googleUser->id;
                $user->email_verified_at = now();
                $user->save();
            }
        }else {
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'email_verified_at' => now(),
                'password' => Hash::make(Str::random(8)),
            ]);

            Profile::create([
                'user_id' => $user->id,
                'bio' => null,
                'city' => null,
                'gender' => null,
                'image' => $googleUser->avatar,
            ]);
        }

        $token = $user->createToken('Laravel-10-Sanctum')->plainTextToken;
        $success['token'] = $token;
        $success['user'] = new UserResource($user);

        return apiResponse($success, "User logged in successfully.", 200);
    }
}
