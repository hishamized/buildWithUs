@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/job-details.css') }}">
@endsection

@section('content')

@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<div class="container my-5">
    <button id="editJobButton" class="btn btn-primary">Edit This Job Post</button>
</div>

<!-- Edit Job Form (Initially Hidden) -->
<div class="container my-5" id="editJobForm" style="display: none;">
    <h5>{{ $job->job_title }}</h5>
    <form action="{{ route('updateJob', ['job' => $job->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Job Title -->
        <div class="form-group">
            <label for="job_title">Job Title</label>
            <input type="text" class="form-control" id="job_title" name="job_title" value="{{ $job->job_title }}" required>
        </div>

        <div class="form-group">
            <label for="job_description"></label>
            <textarea class="form-control" id="job_description" name="job_description" rows="4" required>{{ $job->job_description }}</textarea>
        </div>

        <div class="form-group">
            <label for="job_requirements">Job Requirements</label>
            <textarea class="form-control" id="job_requirements" name="job_requirements" rows="4" required>{{$job->job_requirements}}</textarea>
        </div>

        <div class="form-group">
            <label for="hiring_capacity">Hiring Capacity</label>
            <input type="number" class="form-control" id="hiring_capacity" name="hiring_capacity" required value="{{$job->hiring_capacity}}">
        </div>

        @php
        $siteAddress = json_decode($job->site_address);
        @endphp

        <div class="form-group my-3">
            <label for="street_address">Street Address</label>
            <input type="text" class="form-control" id="street_address" name="street_address" required value="{{ $siteAddress->street_address ?? '' }}">
        </div>

        <div class="form-group my-3">
            <label for="city">City</label>
            <input type="text" class="form-control" id="city" name="city" required value="{{ $siteAddress->city ?? '' }}">
        </div>

        <div class="form-group my-3">
            <label for="state">State</label>
            <input type="text" class="form-control" id="state" name="state" required value="{{ $siteAddress->state ?? '' }}">
        </div>

        <div class="form-group my-3">
            <label for="country">Country</label>
            <input type="text" class="form-control" id="country" name="country" required value="{{ $siteAddress->country ?? '' }}">
        </div>

        <div class="form-group my-3">
            <label for="pin_code">Pin Code</label>
            <input type="text" class="form-control" id="pin_code" name="pin_code" required value="{{ $siteAddress->pin_code ?? '' }}">
        </div>

        <input type="hidden" id="site_address" name="site_address" value="A">

        <div class="form-group my-3">
            <label for="job_type">Job Type:</label>
            <select class="form-control" id="job_type" name="job_type" default="{{$job->job_type}}" required>
                <option value="Daily Wage">Daily Wage</option>
                <option value="Contractual">Contractual</option>
            </select>
        </div>

        <div class="form-group my-3">
            <label for="start_date">Start Date:</label>
            <input type="date" class="form-control" id="start_date" name="start_date" required value="{{$job->start_date}}">
        </div>

        <div class="form-group my-3">
            <label for="end_date">End Date:</label>
            <input type="date" class="form-control" id="end_date" name="end_date" required value="{{$job->end_date}}">
        </div>

        <div class="form-group my-3">
            <label for="expiration_date">Expiration Date:</label>
            <input type="date" class="form-control" id="expiration_date" name="expiration_date" required value="{{$job->expiration_date}}">
        </div>

        <div class="form-group my-3">
            <label for="skill_set">Skill Set</label>
            <input type="text" class="form-control" id="skill_set" name="skill_set" placeholder="Skills (comma-separated)" required value="{{ implode(', ', json_decode($job->skill_set)) }}">
        </div>


        <div class="form-group my-3">
            <label for="wage">Wage:</label>
            <input type="number" class="form-control" id="wage" name="wage" step="0.01" required value="{{$job->wage}}">
        </div>

        <div class="form-group my-3">
            <label for="currency">Currency:</label>
            <select class="form-control" id="currency" name="currency" required default="{$job->currency}">
                <option value="Indian Rupees">Indian Rupees</option>
                <option value="Dollars">Dollars</option>
                <option value="Saudi Arabian Riyal">Saudi Arabian Riyal</option>
                <!-- Add more currency options as needed -->
            </select>
        </div>

        <div class="form-group form-check my-3">
            <strong>Existing Images for this post</strong>
            @if ($job->site_pictures)
            @foreach ( json_decode( $job->site_pictures ) as $picture )
            <div><small> {{$picture}} </small></div>
            @endforeach
            @endif
            <input class="form-check-input" type="checkbox" id="delete_existing_images" name="delete_existing_images">
            <label class="form-check-label" for="delete_existing_images">
                Delete Pre Existing Images
            </label>
        </div>


        <!-- File Input for Adding New Images -->
        <div class="form-group">
            <label for="site_pictures">Add New Images:</label>
            <input type="file" class="form-control" id="site_pictures" name="site_pictures[]" multiple accept="image/*">
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Update Job</button>
    </form>
</div>

<div class="container my-5">
    <h1>Job Details</h1>

    {{-- Display Job Title --}}
    <h2>{{ $job->job_title }}</h2>

    {{-- Display Created At Date --}}
    <p>Created on: {{ $job->created_at->format('F d, Y') }}</p>

    {{-- Display other job details --}}
    <div>
        <h5>Job Description</h5>
        <p>{{ $job->job_description }}</p>
    </div>

    <!-- ... (other template code) ... -->
    {{-- Display Carousel for Job Images --}}
    @if (isset($job->site_pictures) && !empty($job->site_pictures))
    <div class="carousel-container" id="carousel-container">
        <div id="jobImageCarousel" class="carousel">
            <div class="carousel-inner">
                @foreach (json_decode($job->site_pictures) as $key => $image)
                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    <img src="{{ asset('storage/' . $image) }}" alt="Job Image {{ $key }}">
                </div>
                @endforeach
            </div>
            <a class="carousel-control-prev" href="#carousel-container" role="button" id="prevSlide">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carousel-container" role="button" id="nextSlide">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    @endif


    <div>
        <h5>Job Requirements</h5>
        <p>{{ $job->job_requirements }}</p>
    </div>

    <div>
        <h5>Hiring Capacity</h5>
        <p>{{ $job->hiring_capacity }}</p>
    </div>

    <div>
        <h5>Job Type</h5>
        <p>{{ $job->job_type }}</p>
    </div>

    <div>
        <h5>Start Date</h5>
        <p>{{ $job->start_date }}</p>
    </div>

    <div>
        <h5>End Date</h5>
        <p>{{ $job->end_date }}</p>
    </div>

    <div>
        <h5>Expiration Date</h5>
        <p>{{ $job->expiration_date }}</p>
    </div>

    <div>
        <h5>Wage</h5>
        <p>{{ $job->wage }} {{ $job->currency }}</p>
    </div>

    <div>
        <h5>Job Status</h5>
        <p>{{$job->job_status}}</p>
    </div>

    <div>
        <h5>Views</h5>
        <p>{{$job->views}}</p>
    </div>

    <div>
        <h5>No of artisans who applied</h5>
        <p>{{$job->application_count}}</p>
    </div>

    <div class="list-group-item"> <strong>Set of required skills: </strong>
        <ul class="list-group">
            @if($job->skill_set)
            @foreach(json_decode($job->skill_set) as $skill)
            <li class="list-group-item">{{ $skill }}</li>
            @endforeach
            @endif
        </ul>


    </div>
    {{-- Add any other job details you want to display here --}}
</div>

@section('scripts')
<script>
    const carousel = document.querySelector('#jobImageCarousel');
    if (carousel) {
        const prevButton = document.querySelector('#prevSlide');
        const nextButton = document.querySelector('#nextSlide');
        const slides = document.querySelectorAll('.carousel-item');
        let currentSlide = 0;

        prevButton.addEventListener('click', function() {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
        });

        nextButton.addEventListener('click', function() {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
        });
    }

    // Get references to the edit button and edit form
    const editJobButton = document.getElementById('editJobButton');
    const editJobForm = document.getElementById('editJobForm');

    // Add click event listener to the edit button
    editJobButton.addEventListener('click', function() {
        // Toggle form visibility
        if (editJobForm.style.display === 'none' || editJobForm.style.display === '') {
            editJobForm.style.display = 'block';
        } else {
            editJobForm.style.display = 'none';
        }
    });
</script>


@endsection
@endsection
