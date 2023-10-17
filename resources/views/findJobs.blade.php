@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Find Jobs</h1>
            <form action="{{ route('searchJobs') }}" method="POST">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search for jobs" name="query">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </div>
            </form>

            @if(isset($jobs) && count($jobs) > 0)
            <div class="container mt-4">
                <h2 class="text-center mb-3">Matching Jobs</h2>
                <ul class="list-group">
                    @foreach($jobs as $job)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $job->job_title }}</span>
                        <a href="{{ route('job-details', ['id' => $job->id]) }}" class="btn btn-primary">View Job</a>
                    </li>
                    @endforeach
                </ul>
            </div>
            @else
            <div class="container mt-4">
                <p class="text-center">No results found.</p>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection
