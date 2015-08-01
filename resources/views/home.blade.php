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

<body ng-controller="HomeController">

<div class="main">

    @include('templates.header')

    {{--<button ng-click="testFeedback()">test feedback</button>--}}

    <div id="feedback">
        <div ng-repeat="message in feedback_messages track by $index" class="feedback-message">
            [[message]]
        </div>
    </div>

    <div ng-show="loading" id="loading">
        <i class="fa fa-spinner fa-spin"></i>
    </div>

        <button ng-click="debugTotals()">debug totals</button>
    
    <div>

        @include('templates.home.toolbar')

        <div ng-controller="NewTransactionController" id="new-transaction-container" class="">
            @include('templates.home.new-transaction')
        </div>
        
        <div class="main-content">
            <totals-directive
                    totals="totals"
                    getTotals="getTotals()"
                    provideFeedback="provideFeedback()">
            </totals-directive>

            <div ng-controller="TransactionsController" class="flex-grow-2">
                {{--This line had to be inside the div or the scope property--}}
                {{--wouldn't work in the popup--}}

                <?php
                    include($templates . '/popups/home/index.php');
                ?>

                @include('templates.home.transactions')
            </div>


            <div ng-controller="FilterController">
                @include('templates/filter')
                @include('templates/filter-totals')
            </div>
    
        </div>

    </div>
  
</div><!-- .main -->  

@include('templates/footer')
@include('templates/home/footer')
@include('footer')
