<!DOCTYPE html>
<html lang="en" ng-app="budgetApp">
<head>
    <meta charset="UTF-8">
    <title>Budget App</title>

    <?php
        include(base_path().'/resources/views/templates/config.php');
        include($head_links);
    ?>

</head>
<body ng-controller="budgets">

    <?php
        include($header);
        include($templates . '/popups/budget/index.php');
    ?>
    
    <!-- if I used ng-if here, tooltipster didn't work. -->
    <div id="budget" class="main">

        <totals-directive
                totals="totals"
                getTotals="getTotals()">
        </totals-directive>

        <div id="feedback">
            <div ng-repeat="message in feedback_messages track by $index" class="feedback-message">
                [[message]]
            </div>
        </div>

        <div>

            {{--<div class="margin-bottom">--}}
                {{--<input ng-keyup="addFixedToSavings($event.keyCode)" type="text" placeholder="add fixed amount to savings" id="add-fixed-to-savings">--}}
                {{--<input ng-keyup="addPercentageToSavings($event.keyCode)" type="text" placeholder="add percentage of RB to savings" id="add-percentage-to-savings">--}}
            {{--</div>--}}

            @include('templates.budgets.help')
            @include('templates.budgets.fixed-budget-inputs')
            @include('templates.budgets.fixed-budget-table')
            @include('templates.budgets.flex-budget-inputs')
            @include('templates.budgets.flex-budget-table')

        </div>

        <span id="budget_hover_span" class="tooltipster" title=""></span>
    </div>

    <?php include($footer); ?>

    @include('footer')

</body>
</html>