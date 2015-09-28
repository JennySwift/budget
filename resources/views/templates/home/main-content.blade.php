<div class="main-content">

    <totals-directive
            ng-show="show.basic_totals || show.budget_totals"
            sideBarTotals="sideBarTotals"
            totalsLoading="totalsLoading"
            basicTotals="basicTotals"
            fixedbudgettotals="fixedBudgetTotals"
            flexbudgettotals="flexBudgetTotals"
            remainingbalance="remainingBalance"
            totalchanges="totalChanges"
            provideFeedback="provideFeedback()"
            show="show">
    </totals-directive>

    <div ng-controller="TransactionsController" ng-show="tab === 'transactions'" class="flex-grow-2">
        {{--This line had to be inside the div or the scope property--}}
        {{--wouldn't work in the popup--}}
        @include('templates.home.popups.index')

        @include('templates.home.transactions')
    </div>

    <div ng-controller="FilterController" ng-show="tab === 'graphs'" class="flex-grow-2">
        @include('templates.home.graphs')
    </div>


    <div ng-controller="FilterController">
        @include('templates/filter')
    </div>

</div>