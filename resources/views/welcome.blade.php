@extends('layouts.app')

@section('title', 'Welcome to My Laravel App')

@section('content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="container mt-5">
    <h1>Welcome to My Laravel App</h1>
    <p>This is the welcome page content.</p>

</div>
@endsection
