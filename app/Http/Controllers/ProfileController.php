<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            
        ]);
    }

    /**
     * Update the user's profile information.
     */
    // public function update(ProfileUpdateRequest $request): RedirectResponse
    // {
    //     $request->user()->fill($request->validated());

    //     if ($request->user()->isDirty('email')) {
    //         $request->user()->email_verified_at = null;
    //     }

        
    //     $request->user()->save();

    //     return Redirect::route('profile.edit')->with('status', 'profile-updated');
    // }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Update user fields from validated request data
        $user->fill($request->validated());

        // Check if image is uploaded
        if ($request->hasFile('image')) {
            // Get the uploaded image file
            $image = $request->file('image');

            // Define the storage path for the image
            $imagePath = 'avatar/';

            // Generate a unique filename for the image
            $imageName = $user->id.'_'.time().'.'.$image->getClientOriginalExtension();

            // Move the uploaded image to the storage path
            $image->move(public_path($imagePath), $imageName);

            // Update the user's image path in the database
            $user->image = $imagePath . $imageName;
        }

        // Check if email is updated
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Save the updated user data
        $user->save();

        // Redirect back to profile edit page with a status message
        return redirect()->route('profile.edit')->with('status', 'Profile updated successfully');
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

    public function logout()
    {
        Auth::logout();
        return Redirect::to('/');
    }
}
