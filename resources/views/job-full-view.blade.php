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
    <h1>Job Full View</h1>

    @if($userHasApplied)
    <div class="alert alert-success" role="alert">
            <h6 class="mb-0">You have already applied for this job.</h6>
        </div>
        <form method="POST" action="{{ route('applications.cancel') }}" class="my-2">
    @csrf
    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
    <input type="hidden" name="job_id" value="{{ $job->id }}">
    <button type="submit" class="btn btn-danger">Cancel Application</button>
</form>

    @else
    <form method="POST" action="{{ route('applications.accept', ['userId' => Auth::id(), 'jobId' => $job->id]) }}" class="my-2">
        @csrf
        <button type="submit" class="btn btn-success">Apply Now</button>
    </form>
    @endif

    {{-- Display Job Title --}}
    <div> <small>Job Title:</small>
        <h4>{{ $job->job_title }}</h4>
    </div>

    {{-- Display Created At Date --}}
    <p>Created on: {{ $job->created_at->format('F d, Y') }}</p>

    {{-- Display other job details --}}
    <div>
        <h5>Job Description</h5>
        <p>{{ $job->job_description }}</p>
    </div>

    <h4>Pictures of the work site</h4>
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
</script>
@endsection
@endsection
