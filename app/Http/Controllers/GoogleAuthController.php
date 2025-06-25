<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Profile;

use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    //

    public function redirect(){
        return Socialite::driver("google")->redirect();
    }


    public function callBack(){

        try {
            $googleUser = Socialite::driver("google")->user();
        }catch (\Exception $e) {
            return redirect()->back()->with('error', 'Unable to login with your Goole account!');
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

            $profile = Profile::create([
                'user_id' => $user->id,
                'bio' => NULL,
                'city' => NULL,
                'gender'=> NULL,
                'image' => $googleUser->avatar,
            ]);
        }

        Auth::login($user);
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
