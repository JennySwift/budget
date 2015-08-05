@extends('templates.auth.master')

@section('content')
    <div id="login">
        <h1>Login</h1>
        <div class="flex">
            @include('templates.auth.errors')
            @include('templates.auth.login-form')
        </div>
    </div>

@endsection
