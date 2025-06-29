<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\Password;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource as UserResource;

class AuthController extends Controller
{
    //
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if($validator->fails()){
            return apiResponse("validation error", $validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if($user){
            $profile = Profile::create([
                'user_id' => $user->id,
                'bio' => NULL,
                'city' => NULL,
                'gender'=> NULL,
                'image' => "user.png"
            ]);
            $token = $user->createToken('Laravel-10-Sanctum')->plainTextToken;
            $success['token'] = $token;
            $success['user'] = new UserResource($user);
            return apiResponse($success, "User logged in successfully", 200);
        }else{
            return apiResponse([], "Can't create account. Please try again later.", 500);
        }
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if($validator->fails()){
            return apiResponse("Validation error", $validator->errors(), 422);
        }

        if(auth()->attempt(['email' => $request->email, 'password' => $request->password])){
            $token = auth()->user()->createToken('Laravel-10-Sanctum')->plainTextToken;
            $success['token'] = $token;
            $user = auth()->user();
            $success['user'] = new UserResource($user);
            return apiResponse($success, "User logged in successfully", 200);
        }else{
            return apiResponse([], "Wrong email or password", 401);
        }
    }


    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return apiResponse([], "User Logged out successfully", 200);
    }



    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        if($validator->fails()){
            return apiResponse("Validation error", $validator->errors(), 422);
        }

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        $user = auth()->user();
        $user = new UserResource($user);
        return apiResponse($user, "Password updated successfully.", 200);
    }
}
