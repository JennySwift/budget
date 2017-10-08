@extends('templates.auth.master')

@section('content')
    <div id="register">

        <div id="trial-message">
            <p>Want to try it out before registering? You can do so by going <a
                        href="http://budget_playground.jennyswiftcreations.com/login">here</a> and logging in with these details:</p>
            <ul>
                <li>Email: jennyswiftcreations@gmail.com</li>
                <li>Password: abcdefg</li>
            </ul>
        </div>

        <h1>Register</h1>

        <div id="requirements">
            <p>I suggest you use this app with either Mozilla Firefox or Google Chrome. You can try it in other browsers but it may not work as well. I don't know if it works at all with Internet Explorer because I am on a Mac.</p>
            <p>This app may require patience to use. It is still being made.</p>
        </div>

        <div class="flex">
            @include('templates.auth.errors')
            @include('templates.auth.register-form')
        </div>
    </div>
@endsection
