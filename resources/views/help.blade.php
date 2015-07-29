<!doctype html>
<html lang="en" class="" ng-app="budgetApp">
<head>
    <meta charset="UTF-8">
    <title>Budget App</title>

    <?php
    include(base_path().'/resources/views/templates/config.php');
    include($head_links);
    ?>

</head>

<body>

<div class="main">

    @include('templates.header')
    @include('templates.feedback')
    @include('templates/help/help-navigation')

    <div id="help">
        @include('templates/help/help-content')
    </div>



</div>

@include('templates/footer')
@include('templates/home/footer')

@include('footer')
