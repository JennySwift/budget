<div class="main-content">

    <side-bar-totals-directive
            ng-show="show.basic_totals || show.budget_totals"
            show="show">
    </side-bar-totals-directive>

    <div ng-controller="TransactionsController" ng-show="tab === 'transactions'" class="flex-grow-2">
        @include('templates.home.popups.index')
        @include('templates.home.transactions')
    </div>

    <div ng-show="tab === 'graphs'" class="flex-grow-2">
        <graphs-directive></graphs-directive>
    </div>

    <div ng-controller="FilterController">
        @include('templates.shared.filter')
    </div>

</div>