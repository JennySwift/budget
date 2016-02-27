<div id="budget-toolbar">
    {{--<div class="btn-group">--}}
        {{--<button--}}
                {{--v-on:click="tab = 'fixed'"--}}
                {{--v-bind:class="{'selected': tab === 'fixed'}"--}}
                {{--class="btn">Fixed Budgets--}}
        {{--</button>--}}
        {{--<button--}}
                {{--v-on:click="tab = 'flex'"--}}
                {{--v-bind:class="{'selected': tab === 'flex'}"--}}
                {{--class="btn">--}}
            {{--Flex Budgets--}}
        {{--</button>--}}
    {{--</div>--}}
{{--    @include('templates.budgets.help')--}}
    <button v-on:click="toggleNewBudget()" class="btn btn-info">New</button>
</div>