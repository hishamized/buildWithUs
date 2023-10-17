@extends('layouts.app')

@section('title', 'General Profile')

@section('content')

<div class="container my-3">


    @if ( $user->profile)

    <div class="row">
        <div class="row-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">User Information</h5>
                    <ul class="list-unstyled">
                        <li><strong>Name:</strong> {{ $user->name }}</li>
                        <li><strong>Email:</strong> {{ $user->email }}</li>
                    </ul>
                </div>
            </div>
        </div>


        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Profile Picture</h5>
                    <img src="{{ asset('storage/' . ($user->profile->profile_picture ?? 'path_to_default_image.jpg')) }}" alt="Profile Picture" class="img-fluid rounded-circle">
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Contact Information</h5>
                    <ul class="list-unstyled">
                        <li><strong>Street Address:</strong> {{ $user->profile->street_address }}</li>
                        <li><strong>Country:</strong> {{ $user->profile->country }}</li>
                        <li><strong>State:</strong> {{ $user->profile->state }}</li>
                        <li><strong>City:</strong> {{ $user->profile->city }}</li>
                        <li><strong>Pin Code:</strong> {{ $user->profile->pin_code }}</li>
                        <li><strong>Contact No:</strong> {{ $user->profile->contact_no }}</li>
                        <li><strong>Alternate Contact No:</strong> {{ $user->profile->alternate_contact_no }}</li>
                        <li><a href="https://api.whatsapp.com/send?phone={{ $user->profile->contact_no }}" target="_blank" class="btn btn-success">
                                <i class="fa-brands fa-whatsapp" style="color: #ffffff;"></i>
                                <small> Chat on WhatsApp</small>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Additional Information</h5>
                    <ul class="list-unstyled">
                        <li><strong>Date of Birth:</strong> {{ $user->profile->date_of_birth }}</li>
                        <li><strong>Aadhar Card:</strong> {{ $user->profile->adhaar_card ? 'XXXX-XXXX-' . substr($user->profile->adhaar_card, -4) : 'N/A' }}</li>

                        <li><strong>Skill Set:</strong></li>
                        <li>
                            <ul>
                                @if ($user->profile->skill_set)
                                @foreach (json_decode($user->profile->skill_set) as $skill)
                                <li>{{ $skill }}</li>
                                @endforeach
                                @endif
                            </ul>
                        </li>
                        <li><strong>Resume:</strong> <a class="btn btn-success" href="{{ asset('storage/' . $user->profile->resume) }}" target="_blank"><i class="fa-solid fa-file" style="color: #ffffff;"></i> Download Resume</a></li>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>UPI ID:</strong>
                                    <ul class="list-unstyled">
                                        <li>{{$user->profile->upi_id}}</li>
                                        <li>{!! QrCode::size(200)->generate($user->profile->upi_id) !!}</li>
                                    </ul>
                                    <small class="text-muted">Scan the QR Code to obtain UPI ID and then pay using any payment app</small>
                                </div>
                            </div>
                        </div>

                    </ul>
                </div>
            </div>
        </div>
    </div>

    @else
    <div class="alert alert-danger">
        <p>No profile information available. The user has not maintained their profile.</p>
    </div>

    @endif
</div>


@endsection
