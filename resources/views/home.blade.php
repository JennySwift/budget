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

    <?php
        include($templates . '/header.blade.php');
        include($templates . '/messages.php');
    ?>

    {{--<button ng-click="testFeedback()">test feedback</button>--}}

    <div id="feedback">
        <div ng-repeat="message in feedback_messages track by $index" class="feedback-message">[[message]]</div>
    </div>
    
    <div>
    
        <?php include($templates . '/home/toolbar.php'); ?>
    
        <div ng-controller="NewTransactionController" id="new-transaction-container" class="">
            <?php
                include($templates . '/home/new-transaction.php');
            ?>
        </div>
        
        <div class="main-content">
            <totals-directive
                    totals="totals"
                    getTotals="getTotals()"
                    provideFeedback="provideFeedback()">
            </totals-directive>

            {{--<checkbox--}}
                    {{--model="one"--}}
                    {{--for="one">--}}
            {{--</checkbox>--}}

            {{--<checkbox--}}
                    {{--model="two"--}}
                    {{--for="two">--}}
            {{--</checkbox>--}}

            {{--<button ng-click="testControllers()">Do something to transactions controller from home controller</button>--}}
            {{--<div>Home controller num: [[num]]</div>--}}

            {{--<div ng-controller="TransactionsController">transactions controller--}}
                {{--<div>TransactionsController num: [[num]]</div>--}}
            {{--</div>--}}

            {{--<div ng-controller="TransactionsController">[[edit_transaction]]</div>--}}

            {{--<div ng-controller="TransactionsController" class="flex-grow-2">--}}
                {{--[[edit_transaction]]--}}
            {{--</div>--}}

            <div ng-controller="TransactionsController" class="flex-grow-2">
                {{--This line had to be inside the div or the scope property--}}
                {{--wouldn't work in the popup--}}
                <?php
                    include($templates . '/popups/home/index.php');
                    include($templates . '/home/transactions.php');
                ?>
            </div>


            <div ng-controller="FilterController">
                {{--<filter-directive--}}
                        {{--show="show.filter"--}}
                        {{--search = "multiSearch()"--}}
                        {{--accounts = "accounts">--}}
                {{--</filter-directive>--}}
                <?php
                    include($templates . '/filter.php');
                    include($templates . '/filter-totals.php');
                ?>
            </div>
    
        </div>

    </div>
  
</div><!-- .main -->  

<?php include($footer); ?>

@include('footer')
