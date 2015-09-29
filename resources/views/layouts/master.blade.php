<!doctype html>

<html lang="en" class="" ng-app="budgetApp">

<head>
    <meta charset="UTF-8">
    <title>Budget App</title>
    @include('templates.shared.head-links')
</head>

@include('templates.shared.page-loading')

<body>

    @include('templates.shared.loading')

        <div ng-controller=@yield('controller') id=@yield('id', 'nothing') class="main">

            @include('templates.shared.header')

            <feedback-directive></feedback-directive>

            @section('page-content')
            @show

        </div>

    @include('templates.shared.footer')
    @include('templates/home/footer')
    @include('footer')
