<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use Illuminate\Support\Facades\Storage;


class ProfileController extends Controller
{
    public function index()
{
    $user = auth()->user();
    $profile = Profile::where('user_id', $user->id)->first();
    return view('profile.index', compact('user', 'profile'));
}

public function edit()
{
    $user = auth()->user(); // Get the authenticated user
    return view('profile.edit', compact('user'));
}

public function update(Request $request)
{
    // 1. Validation
    $validatedData = $request->validate([
        'profile_picture' => 'image|mimes:jpeg,png,jpg,gif|max:1024', // Example validation for the profile picture, adjust as needed
        'street_address' => 'nullable|string|max:255',
        'country' => 'nullable|string|max:255',
        'state' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:255',
        'pin_code' => 'nullable|string|max:10',
        'contact_no' => 'nullable|string|max:20',
        'alternate_contact_no' => 'nullable|string|max:20',
        'date_of_birth' => 'nullable|date',
        'adhaar_card' => 'nullable|string|max:12',
        'skill_set' => 'nullable|string',
        'resume' => 'nullable|mimes:pdf,doc,docx|max:1024', // Example validation for the resume file, adjust as needed
    ]);

    // 2. Authorization
    $user = auth()->user();
    $profile = $user->profile;

    // Check if the user has a profile and the profile belongs to them
    if ($user->profile && $user->profile->user_id === $user->id) {
        // User has a profile, so update it

        // Delete old files if they exist
        if ($request->hasFile('profile_picture') && Storage::disk('public')->exists($profile->profile_picture)) {
            Storage::disk('public')->delete($profile->profile_picture);
        }

        if ($request->hasFile('resume') && Storage::disk('public')->exists($profile->resume)) {
            Storage::disk('public')->delete($profile->resume);
        }

        // Fill the profile model with the validated data
        $profile->fill($validatedData);

        // Handle file uploads for profile picture and resume if provided
        if ($request->hasFile('profile_picture')) {
            $profile->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        if ($request->hasFile('resume')) {
            $profile->resume = $request->file('resume')->store('resumes', 'public');
        }

        $profile->save(); // Save the updated profile data to the database

        // Redirect back with a success message
        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    } else {
        // User does not have a profile, create one
        $newProfile = new Profile($validatedData); // Create a new Profile model with the validated data

        // Handle file uploads for profile picture and resume if provided
        if ($request->hasFile('profile_picture')) {
            $newProfile->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        if ($request->hasFile('resume')) {
            $newProfile->resume = $request->file('resume')->store('resumes', 'public');
        }

        // Associate the profile with the user
        $newProfile->user()->associate($user);

        $newProfile->save(); // Save the new profile to the database

        // Redirect back with a success message
        return redirect()->route('profile')->with('success', 'Profile created and updated successfully.');
    }
}



}
