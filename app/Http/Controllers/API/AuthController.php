<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
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

class AuthController extends BaseController
{
    //
    public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if($validator->fails()){
            return $this->SendError("validation error", $validator->errors());
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
            return $this->SendResponse($success, "User Login Successfully");
        }else{
            return $this->SendError("Can't create account");
        }
    }


    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if($validator->fails()){
            return $this->SendError("validation error", $validator->errors());
        }

        if(auth()->attempt(['email' => $request->email, 'password' => $request->password])){
            $token = auth()->user()->createToken('Laravel-10-Sanctum')->plainTextToken;
            $success['token'] = $token;
            $user = auth()->user();
            $success['user'] = new UserResource($user);
            return $this->SendResponse($success, "User Login Successfully");
        }else{
            return $this->SendError("Wrong E-mail or Password");
        }
    }


    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return $this->SendResponse("User Logged out successfully", "User Logged out successfully");
    }



    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        if($validator->fails()){
            return $this->SendError("validation error", $validator->errors());
        }

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        $user = auth()->user();
        $user = new UserResource($user);
        return $this->SendResponse($user, "password-updated successfully");
    }
}
