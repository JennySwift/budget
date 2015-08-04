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

@include('templates.page-loading')

<body ng-controller="BaseController">

@include('templates.loading')

<div ng-controller="HomeController" class="main">

    @include('templates.header')

    @include('templates.feedback')
    
    <div>

        @include('templates.home.toolbar')

        <div ng-controller="NewTransactionController" id="new-transaction-container" class="">
            @include('templates.home.new-transaction')
        </div>
        
        @include('templates.home.main-content')

    </div>
  
</div>

@include('templates/footer')
@include('templates/home/footer')
@include('footer')
