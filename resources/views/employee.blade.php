@extends('layouts.app')

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

<div class="container my-4">
    <h1>Welcome to Employee Mode</h1>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#" id="button1">
                                Relevant Jobs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" id="button2">
                                Search Job
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" id="button3">
                                My applications
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" id="button4">
                                Option 4
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Content Display -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container-fluid" id="content1" style="display: none;">
                    <p>These are some of the Jobs which rquire the skills that you have got based on your profile.</p>
                    @foreach ($jobs as $job)
                    @php
                    // Decode the JSON string to an array for both user skills and job requirements.
                    $userSkills = json_decode($user->profile->skill_set);
                    $jobRequirements = json_decode($job->skill_set);

                    // Use array_intersect to find matching skills.
                    $matchingSkills = array_intersect($userSkills, $jobRequirements);
                    @endphp

                    @if (!empty($matchingSkills))
                    <!-- Display the job information because there are matching skills -->
                    <div class="job">
                        <li class="list-group-item">
                            <div> <Strong>Job Title: </Strong>{{ $job->job_title }}</div>
                            <div> <strong> Posted On: </strong>{{ $job->created_at->format('Y-m-d') }}</div>
                            <div><span> {{$job->views}} Views</span> <span> {{$job->application_count}} Applications </span> </div>
                            <a href="{{ route('jobFullView', ['id' => $job->id]) }}" class="btn btn-primary btn-sm float-right">View Full Job</a>
                        </li>
                        <hr>
                    </div>
                    @else
                    <h6>Empty</h6>
                    @endif
                    @endforeach

                </div>
                <div class="container-fluid" id="content2" style="display: none;">
                    <div class="container mt-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="job-search-input" placeholder="Enter your job skills as keywords">
                                    <button class="btn btn-primary" id="job-search-button">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container mt-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div id="job-search-results">
                                    <!-- Display search results here -->
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="container-fluid" id="content3" style="display: none;">
                    <div class="container my-5">

                        @if ($applications->isEmpty())
                        <p>You haven't submitted any applications yet.</p>
                        @else
                        <ul>
                        <h3>Your Applications</h3>
                            @foreach ($applications as $application)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0"><strong>Job Title:</strong> {{ $application->job->job_title }}</h5>
                                        <p class="mb-1"><strong>Posted By:</strong> {{ $application->job->user->name }}</p>
                                        <p class="mb-0"><strong>Posted On:</strong> {{ $application->job->created_at->format('F d, Y') }}</p>
                                    </div>
                                    <div>
                                        <a href="{{ route('jobFullView', ['id' => $application->job->id]) }}" class="btn btn-primary">View Job</a>
                                    </div>
                                </div>
                            </li>
                            <hr>
                            @endforeach
                        </ul>
                        @endif
                    </div>

                </div>
                <div class="container-fluid" id="content4" style="display: none;">
                    Content4
                </div>
            </main>
        </div>
    </div>

</div>

@section('scripts')


<script>
    // JavaScript to handle button clicks and show/hide content
    $(document).ready(function() {
        $("#button1").click(function() {
            $("#content1").show();
            $("#content2, #content3, #content4").hide();
        });
        $("#button2").click(function() {
            $("#content2").show();
            $("#content1, #content3, #content4").hide();
        });
        $("#button3").click(function() {
            $("#content3").show();
            $("#content1, #content2, #content4").hide();
        });
        $("#button4").click(function() {
            $("#content4").show();
            $("#content1, #content2, #content3").hide();
        });

        // Attach a click event handler to the search button
        $('#job-search-button').click(function() {
            let searchText = $('#job-search-input').val();

            // Send an AJAX request to the EmployeeController
            $.ajax({
                type: 'POST',
                url: '/employee/search', // Replace with your actual route
                data: {
                    _token: '{{ csrf_token() }}', // Add the CSRF token
                    searchText: searchText
                },
                success: function(data) {
                    // $('#job-search-results').html(data);
                    document.getElementById('job-search-results').innerHTML = data.html;

                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    });
</script>

@endsection
@endsection
