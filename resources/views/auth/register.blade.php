@extends('templates.auth.master')

@section('content')
    <div id="register">
        <h1>Register</h1>
        <div class="flex">
            @include('templates.auth.errors')
            @include('templates.auth.register-form')
        </div>
    </div>
@endsection
