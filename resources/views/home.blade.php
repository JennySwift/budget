
@extends('layouts.master')

@section('controller', 'HomeController')

@section('page-content')

<div>

    @include('templates.home.toolbar')

    <div ng-controller="NewTransactionController" id="new-transaction-container" class="">
        @include('templates.home.new-transaction')
    </div>

    @include('templates.home.main-content')

</div>

@stop
