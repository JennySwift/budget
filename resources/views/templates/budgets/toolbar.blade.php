<div id="budget-toolbar">
    {{--<div class="btn-group">--}}
        {{--<button--}}
                {{--ng-click="tab = 'fixed'"--}}
                {{--ng-class="{'selected': tab === 'fixed'}"--}}
                {{--class="btn">Fixed Budgets--}}
        {{--</button>--}}
        {{--<button--}}
                {{--ng-click="tab = 'flex'"--}}
                {{--ng-class="{'selected': tab === 'flex'}"--}}
                {{--class="btn">--}}
            {{--Flex Budgets--}}
        {{--</button>--}}
    {{--</div>--}}
{{--    @include('templates.budgets.help')--}}
    <button ng-click="toggleNewBudget()" class="btn btn-info">New</button>
</div>