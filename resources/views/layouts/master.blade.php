<!doctype html>

<html lang="en" class="" ng-app="budgetApp">

<head>
    <meta charset="UTF-8">
    <title>Budget App</title>
    @include('templates.head-links')
</head>

@include('templates.page-loading')

<body ng-controller="BaseController">

    @include('templates.loading')

    <div ng-controller=@yield('controller') id=@yield('id', 'nothing') class="main">

        @include('templates.header')

        @include('templates.feedback')

        @section('page-content')
        @show

    </div>

    @include('templates/footer')
    @include('templates/home/footer')
    @include('footer')