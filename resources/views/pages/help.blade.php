@extends('layouts.master')

{{--I don't actually use this controller,
but it's so I have a value for the master template--}}
@section('controller', 'HelpController')

@section('page-content')

    @include('templates.shared.header')
    @include('templates.help.help-navigation')

    <h1>Help</h1>

    <div id="help">
        @include('templates.help.help-content')
    </div>

@stop
