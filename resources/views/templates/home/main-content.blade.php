<div class="main-content">

    <totals-directive
            ng-show="show.basic_totals || show.budget_totals"
            {{--totals="totals"--}}
            basicTotals="basicTotals"
            fixedbudgettotals="fixedBudgetTotals"
            flexbudgettotals="flexBudgetTotals"
            remainingbalance="remainingBalance"
            totalchanges="totalChanges"
            getTotals="getTotals()"
            provideFeedback="provideFeedback()"
            show="show">
    </totals-directive>

    <div ng-controller="TransactionsController" ng-show="tab === 'transactions'" class="flex-grow-2">
        {{--This line had to be inside the div or the scope property--}}
        {{--wouldn't work in the popup--}}

        @include('templates.popups.home.index')

        @include('templates.home.transactions')
    </div>

    <div ng-controller="FilterController" ng-show="tab === 'graphs'" class="flex-grow-2">
        @include('templates.home.graphs')
    </div>


    <div ng-controller="FilterController">
        @include('templates/filter')
        @include('templates/filter-totals')
    </div>

</div>