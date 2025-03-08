<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
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
        if ($user->profile) {
            $user->profile->city = $request->city;
            $user->profile->bio = $request->bio;
            $user->profile->gender = $request->gender;

            $old_image = $user->profile->image;
            if($request->hasfile('image')){
                $image = $request->image;
                $newImage = time() . $image->getClientOriginalName();
                $image->move('uploads/users/', $newImage);
                $user->profile->image = $newImage;
            }
            $profile = $user->profile->save();

            if($profile && $old_image != "user.png" && $request->hasfile('image')){
                $old_image = 'uploads/users/' . $old_image;
                if (File::exists($old_image)) {
                    File::delete($old_image);
                }
            }
        }
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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
