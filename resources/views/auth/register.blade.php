@extends('templates.auth.master')

@section('content')
    <div id="register">

        <h1>Register</h1>

        <div id="requirements">
            <p>If you want to try out the app with pre-populated data, click <a
                        href="auth/login">here.</a></p>
            <p>I suggest you use this app with either Mozilla Firefox or Google Chrome. You can try it in other browsers but it may not work as well. I don't know if it works at all with Internet Explorer because I am on a Mac.</p>
            <p>This app may require patience to use. It is still being made.</p>
        </div>

        <div class="flex">
            @include('templates.auth.errors')
            @include('templates.auth.register-form')
        </div>
    </div>
@endsection
