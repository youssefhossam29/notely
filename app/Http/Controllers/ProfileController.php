<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
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
        return view('profile.edit', [
            'user' => Auth::user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $user->profile->city = $request->city;
        $user->profile->bio = $request->bio;
        $user->profile->gender = $request->gender;

        $old_image = $user->profile->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $newImage = Str::random(10) . time() . $image->getClientOriginalName();
            $image->move('uploads/users/', $newImage);
            $user->profile->image = $newImage;
        }
        $saved = $user->profile->save();

        if($saved && $old_image != "user.png" && $request->hasfile('image') && !Str::startsWith($old_image, 'https://')){
            $old_image = 'uploads/users/' . $old_image;
            if (File::exists($old_image)) {
                File::delete($old_image);
            }
        }

        return Redirect::route('profile.edit')->with('success', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
