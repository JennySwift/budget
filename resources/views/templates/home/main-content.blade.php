<div class="main-content">
    <totals-directive
            totals="totals"
            getTotals="getTotals()"
            provideFeedback="provideFeedback()">
    </totals-directive>

    <div ng-controller="TransactionsController" class="flex-grow-2">
        {{--This line had to be inside the div or the scope property--}}
        {{--wouldn't work in the popup--}}

        @include('templates.popups.home.index')

        @include('templates.home.transactions')
    </div>


    <div ng-controller="FilterController">
        @include('templates/filter')
        @include('templates/filter-totals')
    </div>

</div>