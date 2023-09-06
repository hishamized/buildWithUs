@extends('layouts.app')

@section('title', 'Search people')

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


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Find People</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('findPeople') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="search" class="col-md-4 col-form-label text-md-right">Search</label>

                            <div class="col-md-6">
                                <input id="search" type="text" class="form-control @error('search') is-invalid @enderror" name="search" value="{{ old('search') }}" required autofocus>

                                @error('search')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0 my-2">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if (isset($results) && count($results) > 0)
                <div class="card mt-4">
                    <div class="card-header">Search Results</div>

                    <div class="card-body">
                        <ul class="list-group">
                            @foreach ($results as $user)
                            <li class="list-group-item">
                            <div>
                               <span> {{ $user->name }} </span>
                               <small>( @ {{ $user->username }} )</small> <br>
                               <strong> ({{ $user->email }})</strong>
                            </div>
                                <div>
                                <a href="{{ route('generalProfile', ['id' => $user->id]) }}" class="btn btn-primary btn-sm mr-2">View User Profile</a>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @elseif(isset($results) && count($results) == 0)
                <div class="card mt-4">
                    <div class="card-header">No Users Found</div>

                    <div class="card-body">
                        <p>No users matched your search criteria.</p>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

@endsection
