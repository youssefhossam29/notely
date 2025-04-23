<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource as UserResource;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProfileController extends BaseController
{
    //
    public function show(){
        $user = Auth::user();
        if($user->profile == null){
            $user->profile()->create([
                'user_id' => $user->id,
                'bio' => NULL,
                'city' => NULL,
                'gender'=> NULL,
                'image' => "user.png"
            ]);
            $user->refresh();
        }
        $user = new UserResource($user);
        return $this->SendResponse($user, "User selected Successfully");
    }


    public function update(ProfileUpdateRequest $request)
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $user->profile->city = $request->city;
        $user->profile->bio = $request->bio;
       // $user->profile->gender = $request->gender;

        if($request->gender == null){
            $user->profile->gender = $user->profile->gender;
        }else{
            $user->profile->gender = $request->gender;
        }


        $old_image = $user->profile->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $newImage = Str::random(10) . time() . $image->getClientOriginalName();
            $image->move('uploads/users/', $newImage);
            $user->profile->image = $newImage;
        }
        $saved = $user->profile->save();

        if($saved && $old_image != "user.png" && $request->hasfile('image')){
            $old_image = 'uploads/users/' . $old_image;
            if (File::exists($old_image)) {
                File::delete($old_image);
            }
        }

        $user = new UserResource($user);
        return $this->SendResponse($user, "Profile updated Successfully");
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request){
        $validator = Validator::make($request->all(), [
            'password' => ['required', 'current_password']
        ]);

        if($validator->fails()){
            return $this->SendError("validation error", $validator->errors());
        }

        $user = $request->user(); //
        $user->tokens()->delete();
        $user->delete();
        return $this->SendResponse("Account deleted Successfully", "Account deleted Successfully");
    }

}
