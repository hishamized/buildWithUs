@extends('layouts.app') <!-- Use your layout file, e.g., 'app.blade.php' -->
@section('title', 'My Profile')
@section('content') <!-- Start of content section -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>User Profile</h3>
                </div>
                <div class="card-body">
                    @if ($profile)
                    <!-- Display profile details in a list -->
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
    <li class="list-group-item"><strong>Skill Set:</strong> {{ $profile->skill_set ?? 'N/A' }}</li>

    <!-- Display Profile Picture as an Image -->
    <li class="list-group-item">
        <strong>Profile Picture:</strong>
        @if ($profile->profile_picture)
            <img src="{{ asset('storage/' . $profile->profile_picture) }}" alt="Profile Picture" width="100">
        @else
            N/A
        @endif
    </li>

    <!-- Display Resume as an Icon with a Link -->
    <li class="list-group-item">
        <strong>Resume:</strong>
        @if ($profile->resume)
            <a href="{{ asset('storage/' . $profile->resume) }}" target="_blank">
                <img src="{{ asset('path-to-file-icon.png') }}" alt="Resume Icon" width="32" height="32">
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
        <!-- Input fields for updating the profile -->
        <div class="form-group">
            <label for="profile_picture">Profile Picture:</label>
            <input type="file" class="form-control" id="profile_picture" name="profile_picture" >
        </div>

        <div class="form-group">
            <label for="street_address">Street Address:</label>
            <input type="text" class="form-control" id="street_address" name="street_address" value="{{ $profile->street_address ?? 'N/A' }}">
        </div>

        <div class="form-group">
            <label for="country">Country:</label>
            <input type="text" class="form-control" id="country" name="country" value="{{ $profile->country ?? 'N/A' }}">
        </div>

        <div class="form-group">
            <label for="state">State:</label>
            <input type="text" class="form-control" id="state" name="state" value="{{ $profile->state ?? 'N/A' }}">
        </div>

        <div class="form-group">
            <label for="city">City:</label>
            <input type="text" class="form-control" id="city" name="city" value="{{ $profile->city ?? 'N/A' }}">
        </div>

        <div class="form-group">
            <label for="pin_code">Pin Code:</label>
            <input type="text" class="form-control" id="pin_code" name="pin_code" value="{{ $profile->pin_code ?? 'N/A' }}">
        </div>

        <div class="form-group">
            <label for="contact_no">Contact Number:</label>
            <input type="text" class="form-control" id="contact_no" name="contact_no" value="{{ $profile->contact_no ?? 'N/A' }}">
        </div>

        <div class="form-group">
            <label for="alternate_contact_no">Alternate Contact Number:</label>
            <input type="text" class="form-control" id="alternate_contact_no" name="alternate_contact_no" value="{{ $profile->alternate_contact_no }}">
        </div>

        <div class="form-group">
            <label for="date_of_birth">Date of Birth:</label>
            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ $profile->date_of_birth }}">
        </div>

        <div class="form-group">
            <label for="adhaar_card">Aadhaar Card:</label>
            <input type="text" class="form-control" id="adhaar_card" name="adhaar_card" value="{{ $profile->adhaar_card }}">
        </div>

        <div class="form-group">
            <label for="skill_set">Skill Set:</label>
            <textarea class="form-control" id="skill_set" name="skill_set" rows="4"></textarea>
        </div>


        <div class="form-group">
            <label for="resume">Resume:</label>
            <input type="file" class="form-control" id="resume" name="resume">
        </div>

        <button type="submit" class="btn btn-success">Save Changes</button>
    </form>

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
</script>
@endsection

@endsection <!-- End of content section -->
