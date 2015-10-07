

<div ng-cloak ng-show="show.filter" id="filter" class="margin-bottom">

{{--    @include('templates.shared.filter-totals')--}}
    <filter-totals-directive
            {{--filterTotals="filterTotals"--}}
            show="show"
            filter="filter">
    </filter-totals-directive>

    <div class="btn-group">
        <button ng-click="filterTab = 'show'" class="btn btn-success">Show</button>
        <button ng-click="filterTab = 'hide'" class="btn btn-danger">Hide</button>
    </div>

    <div ng-if="filterTab === 'show'">
        Show tab
    </div>
    <div ng-if="filterTab === 'hide'">
        Hide tab
    </div>

    {{--@include('directive-templates.filter.accounts')--}}

    <filter-accounts-directive
            filter="filter"
            filterTab="filterTab"
            runFilter="runFilter()">
    </filter-accounts-directive>

    {{--@include('templates.home.filter.types')--}}

    <filter-types-directive
            filter="filter"
            filterTab="filterTab"
            runFilter="runFilter()">
    </filter-types-directive>

    {{--@include('templates.home.filter.description')--}}

    <filter-description-directive
            filter="filter"
            filterTab="filterTab"
            runFilter="runFilter()">
    </filter-description-directive>

{{--    @include('templates.home.filter.merchant')--}}

    <filter-merchant-directive
            filter="filter"
            filterTab="filterTab"
            runFilter="runFilter()">
    </filter-merchant-directive>

    {{--@include('templates.home.filter.tags')--}}

    <filter-tags-directive
            filter="filter"
            filterTab="filterTab"
            runFilter="runFilter()"
            budgets="budgets">
    </filter-tags-directive>

    {{--@include('templates.home.filter.date')--}}

    <filter-date-directive
            filter="filter"
            filterTab="filterTab"
            runFilter="runFilter()">
    </filter-date-directive>

    {{--@include('templates.home.filter.amount')--}}

    <filter-total-directive
            filter="filter"
            filterTab="filterTab"
            runFilter="runFilter()">
            {{--clearFilterField="clearFilterField()">--}}
    </filter-total-directive>

    @include('templates.home.filter.reconciled')
    @include('templates.home.filter.num-budgets')

    {{--<label>bug fix</label>--}}
    {{--<input--}}
        {{--ng-model="filter.bugFix"--}}
        {{--ng-change="filterTransactions()"--}}
        {{--type="checkbox">--}}


</div>    