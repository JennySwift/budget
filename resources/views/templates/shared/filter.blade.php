

<div ng-cloak ng-show="show.filter" id="filter" class="margin-bottom">

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

    <select ng-options="savedFilter as savedFilter.name for savedFilter in savedFilters"
            ng-model="savedFilter"
            ng-change="chooseSavedFilter(savedFilter)"
            class="form-control">

    </select>

    <div class="toolbar-filter">
        <filter-toolbar-directive></filter-toolbar-directive>
    </div>

    <div class="flex">
        <filter-totals-directive
                show="show"
                filter="filter">
        </filter-totals-directive>

        <div>
            <filter-accounts-directive
                    filter="filter"
                    filterTab="filterTab"
                    runFilter="runFilter()">
            </filter-accounts-directive>

            <filter-types-directive
                    filter="filter"
                    filterTab="filterTab"
                    runFilter="runFilter()">
            </filter-types-directive>

            <filter-description-directive
                    filter="filter"
                    filterTab="filterTab"
                    runFilter="runFilter()">
            </filter-description-directive>

            <filter-merchant-directive
                    filter="filter"
                    filterTab="filterTab"
                    runFilter="runFilter()">
            </filter-merchant-directive>

            <filter-tags-directive
                    filter="filter"
                    filterTab="filterTab"
                    runFilter="runFilter()"
                    budgets="budgets">
            </filter-tags-directive>

            <filter-date-directive
                    filter="filter"
                    filterTab="filterTab"
                    runFilter="runFilter()">
            </filter-date-directive>

            <filter-total-directive
                    filter="filter"
                    filterTab="filterTab"
                    runFilter="runFilter()">
                {{--clearFilterField="clearFilterField()">--}}
            </filter-total-directive>

            @include('templates.home.filter.reconciled')
            @include('templates.home.filter.num-budgets')
        </div>
    </div>





    {{--<label>bug fix</label>--}}
    {{--<input--}}
        {{--ng-model="filter.bugFix"--}}
        {{--ng-change="filterTransactions()"--}}
        {{--type="checkbox">--}}


</div>    