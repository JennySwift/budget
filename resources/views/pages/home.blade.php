
@extends('layouts.master')

@section('controller', 'HomeController')

@section('page-content')

<div>

    @include('templates.home.toolbar')

    <div
        ng-controller="NewTransactionController"
        ng-show="tab === 'transactions'"
        id="new-transaction-container">

        @include('templates.home.new-transaction')

        {{--<input ng-model="dateTest" type="text" formatted-date/>--}}

        {{--<button ng-model="testBtn" id="test-btn">Test me!</button>--}}
        {{--<input type="text" id="test-input"/>--}}

    </div>

    <h1>Just testing</h1>

    @include('templates.home.main-content')

    {{--<script id="totals">--}}
        {{--@include('directives.totals')--}}
    {{--</script>--}}

</div>

@stop
