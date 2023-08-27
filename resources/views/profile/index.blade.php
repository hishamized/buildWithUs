@extends('layouts.app')
@section('title', 'My Profile')
@section('content')



@if (session('error'))
<div class="alert alert-danger">
    <ul>
        <li>{{ session('error') }}</li>
    </ul>
</div>
@endif


@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>User Profile</h3>
                </div>
                <div class="card-body">
                    @if ($profile)

                    <ul class="list-group">
                        <li class="list-group-item"><strong>Name:</strong> {{ $user->name }}</li>
                        <li class="list-group-item"><strong>Email:</strong> {{ $user->email }}</li>
                        <li class="list-group-item"><strong>Street Address:</strong> {{ $profile->street_address ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Country:</strong> {{ $profile->country ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>State:</strong> {{ $profile->state ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>City:</strong> {{ $profile->city ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Pin Code:</strong> {{ $profile->pin_code ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Contact No:</strong> {{ $profile->contact_no ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Alternate Contact No:</strong> {{ $profile->alternate_contact_no ?? 'N/A' }}</li>
                        <li class="list-group-item"><strong>Date of Birth:</strong> {{ $profile->date_of_birth ? date('Y-m-d', strtotime($profile->date_of_birth)) : 'N/A' }}</li>

                        <li class="list-group-item"><strong>Aadhaar Card:</strong> {{ $profile->adhaar_card ?? 'N/A' }}</li>
                        <li class="list-group-item"> <strong>Set of Skills: </strong>
                            <ul class="list-group">
                                @if($profile->skill_set)
                                @foreach(json_decode($profile->skill_set) as $skill)
                                <li class="list-group-item">{{ $skill }}</li>
                                @endforeach
                                @endif
                            </ul>


                        </li>



                        <li class="list-group-item">
                            <strong>Profile Picture:</strong>
                            @if ($profile->profile_picture)
                            <img src="{{ asset('storage/' . $profile->profile_picture) }}" alt="Profile Picture" width="100">
                            @else
                            N/A
                            @endif
                        </li>


                        <li class="list-group-item">
                            <strong>Resume:</strong>
                            @if ($profile->resume)
                            <a href="{{ asset('storage/' . $profile->resume) }}" target="_blank">
                                <i class="fa-solid fa-file-pdf"></i>
                                <p>Open Resume</p>
                            </a>
                            @else
                            N/A
                            @endif
                        </li>
                    </ul>

                    @else
                    <p>You have not updated your profile details yet. Please update your profile.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <button id="update-profile-button" class="btn btn-primary">Update Profile</button>

    <form id="update-profile-form" style="display: none;" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="profile_picture">Profile Picture:</label>
            <input type="file" class="form-control" id="profile_picture" name="profile_picture">
        </div>

        <div class="form-group">
            <label for="street_address">Street Address:</label>
            <input type="text" class="form-control" id="street_address" name="street_address" value="{{ $user->profile ? $user->profile->adhaar_card : '' }}">
        </div>


        <div class="form-group">
            <label for="country">Country:</label>
            <input type="text" class="form-control" id="country" name="country" value="{{ $user->profile ? $user->profile->country : '' }}">
        </div>

        <div class="form-group">
            <label for="state">State:</label>
            <input type="text" class="form-control" id="state" name="state" value="{{ $user->profile ? $user->profile->state : '' }}">
        </div>

        <div class="form-group">
            <label for="city">City:</label>
            <input type="text" class="form-control" id="city" name="city" value="{{ $user->profile ? $user->profile->city : '' }}">
        </div>

        <div class="form-group">
            <label for="pin_code">Pin Code:</label>
            <input type="text" class="form-control" id="pin_code" name="pin_code" value="{{ $user->profile ? $user->profile->pin_code : '' }}">
        </div>

        <div class="form-group">
            <label for="contact_no">Contact Number:</label>
            <input type="text" class="form-control" id="contact_no" name="contact_no" value="{{ $user->profile ? $user->profile->contact_no : '' }}">
        </div>

        <div class="form-group">
            <label for="alternate_contact_no">Alternate Contact Number:</label>
            <input type="text" class="form-control" id="alternate_contact_no" name="alternate_contact_no" value="{{ $user->profile ? $user->profile->alternate_contact_no : '' }}">
        </div>

        <div class="form-group">
            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ $user->profile ? $user->profile->date_of_birth : '' }}">
        </div>

        <div class="form-group">
            <label for="adhaar_card">Aadhaar Card:</label>
            <input type="text" class="form-control" id="adhaar_card" name="adhaar_card" value="{{ $user->profile ? $user->profile->adhaar_card : '' }}">
        </div>

        <div class="form-group">
            <label for="skill_set">Skill Set (Separate skills with commas):</label>
            <input type="text" class="form-control" id="skill_set" name="skill_set" value="{{ old('skill_set', implode(', ', $profile->skillset ?? [])) }}">
        </div>



        <div class="form-group">
            <label for="resume">Resume:</label>
            <input type="file" class="form-control" id="resume" name="resume">
        </div>

        <button type="submit" class="btn btn-success">Save Changes</button>
    </form>

</div>


<div class="container my-5">

    <button id="change-password-btn" class="btn btn-primary">Change Password</button>


    <div id="change-password-form" style="display: none;">

        <h4>Change Password for {{ auth()->user()->name }}</h4>

        <form method="POST" action="{{ route('change-password') }}">
            @csrf

            <div class="card-body">

                <div class="form-group">
                    <strong>Your username is : {{ auth()->user()->username }}</strong>
                </div>


                <div class="form-group">
                    <label for="current_password">Current Password:</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" required>
                </div>


                <div class="form-group">
                    <label for="new_password">New Password:</label>
                    <input type="password" class="form-control" id="new_password" name="new_password" required>
                </div>


                <div class="form-group">
                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>

                <button type="submit" class="btn btn-primary">Change Password</button>
        </form>
    </div>

</div>



@section('scripts')
<script>
    // Get references to the button and the form
    const updateProfileButton = document.getElementById('update-profile-button');
    const updateProfileForm = document.getElementById('update-profile-form');

    // Add a click event listener to the button
    updateProfileButton.addEventListener('click', function() {
        // Toggle the form's visibility
        if (updateProfileForm.style.display === 'none') {
            updateProfileForm.style.display = 'block';
        } else {
            updateProfileForm.style.display = 'none';
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const changePasswordButton = document.getElementById('change-password-btn');
        const changePasswordForm = document.getElementById('change-password-form');

        changePasswordButton.addEventListener('click', function() {
            // Toggle the visibility of the password change form
            if (changePasswordForm.style.display === 'none') {
                changePasswordForm.style.display = 'block';
            } else {
                changePasswordForm.style.display = 'none';
            }
        });
    });
</script>
@endsection

@endsection
