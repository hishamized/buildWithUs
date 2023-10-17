<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


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
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {

        $validatedData = $request->validate([
            'profile_picture' => 'image|mimes:jpeg,png,jpg,gif|max:1024',
            'street_address' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'pin_code' => 'nullable|string|max:10',
            'contact_no' => 'nullable|string|max:20',
            'alternate_contact_no' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'adhaar_card' => 'nullable|string|max:12',
            'upi_id' => ['nullable', 'string', 'max:20', 'upi_id_format'],
            'skill_set' => 'nullable|string',
            'resume' => 'nullable|mimes:pdf,doc,docx|max:1024',
        ]);


        $user = auth()->user();
        $profile = $user->profile;


        if ($user->profile && $user->profile->user_id === $user->id) {



            if ($request->hasFile('profile_picture') && Storage::disk('public')->exists($profile->profile_picture)) {
                Storage::disk('public')->delete($profile->profile_picture);
            }

            if ($request->hasFile('resume') && Storage::disk('public')->exists($profile->resume)) {
                Storage::disk('public')->delete($profile->resume);
            }


            $profile->fill($validatedData);


            if ($request->hasFile('profile_picture')) {
                $profile->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
            }

            if ($request->hasFile('resume')) {
                $profile->resume = $request->file('resume')->store('resumes', 'public');
            }

            $profile->skill_set = json_encode(explode(', ', $request->input('skill_set')));

            $profile->save();


            return redirect()->route('profile')->with('success', 'Profile updated successfully.');
        } else {

            $newProfile = new Profile($validatedData);


            if ($request->hasFile('profile_picture')) {
                $newProfile->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
            }

            if ($request->hasFile('resume')) {
                $newProfile->resume = $request->file('resume')->store('resumes', 'public');
            }

            $newProfile->skill_set = json_encode(explode(', ', $request->input('skill_set')));

            $newProfile->user()->associate($user);

            $newProfile->save();

            return redirect()->route('profile')->with('success', 'Profile created and updated successfully.');
        }
    }

    public function generalProfile($id)
    {

        $user = User::findOrFail($id);


        $profile = $user->profile;

        return view('generalProfile', compact('user', 'profile'));
    }

    public function showFindPeople(){

        return view('findPeople');
    }

    public function findPeople(Request $request) {

        $validatedData = $request->validate([
            'search' => 'required|string|max:255',
        ]);

        $search = $validatedData['search'];

        $results = User::where('name', 'LIKE', "%{$search}%")
        ->orWhere('username', 'LIKE', "%{$search}%")
        ->orWhere('email', 'LIKE', "%{$search}%")
        ->orWhereHas('profile', function ($query) use ($search) {
            $query->where('street_address', 'LIKE', "%{$search}%")
                ->orWhere('country', 'LIKE', "%{$search}%")
                ->orWhere('state', 'LIKE', "%{$search}%")
                ->orWhere('city', 'LIKE', "%{$search}%")
                ->orWhere('pin_code', 'LIKE', "%{$search}%")
                ->orWhere('contact_no', 'LIKE', "%{$search}%")
                ->orWhere('alternate_contact_no', 'LIKE', "%{$search}%")
                ->orWhere('date_of_birth', 'LIKE', "%{$search}%")
                ->orWhere('adhaar_card', 'LIKE', "%{$search}%")
                ->orWhereRaw("JSON_SEARCH(skill_set, 'one', ?) IS NOT NULL", ["%$search%"]);
        })
        ->get();


        return view('findPeople', compact('results'));
    }

}


