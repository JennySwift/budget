@extends('templates.auth.master')

@section('content')
    <div id="login">
        <h1>Login</h1>

        <div id="requirements">
            <p>You can log in with the following email and password. This will give you access to an existing account with pre-populated data. If you want to instead try it out without the pre-populated data, you can register for the demo version
                <a href="/auth/register">here.</a></p>
            <ul>
                <li>Email: jennyswiftcreations@gmail.com</li>
                <li>Password: abcdefg</li>
            </ul>
        </div>

        <div id="requirements">
            <p>I suggest you use this app with either Mozilla Firefox or Google Chrome. You can try it in other browsers but it may not work as well. I don't know if it works at all with Internet Explorer because I am on a Mac.</p>
            <p>This app may require patience to use. It is still being made.</p>
        </div>


        <div class="flex">
            @include('templates.auth.errors')
            @include('templates.auth.login-form')
        </div>
    </div>

@endsection
