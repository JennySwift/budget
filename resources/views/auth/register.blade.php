@extends('templates.auth.master')

@section('content')
    <div id="register">
        <h1>Register</h1>

        <div id="trial-message">
            <p>Want to try it out before registering? You can do so by going <a
                        href="http://budget_playground.jennyswiftcreations.com/auth/login">here</a> and logging in with these details:</p>
            <ul>
                <li>Email: jennyswiftcreations@gmail.com</li>
                <li>Password: abcdefg</li>
            </ul>
        </div>

        <div class="flex">
            @include('templates.auth.errors')
            @include('templates.auth.register-form')
        </div>
    </div>
@endsection
