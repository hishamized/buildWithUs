@extends('layouts.app')

@section('content')

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
                                Option 2
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" id="button3">
                                Option 3
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
                            <a href="{{ route('job-details', ['job' => $job->id]) }}" class="btn btn-primary btn-sm float-right">View Full Details</a>
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
                                    <input type="text" class="form-control" id="job-search-input" placeholder="Enter job keywords">
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
                    Content3
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
