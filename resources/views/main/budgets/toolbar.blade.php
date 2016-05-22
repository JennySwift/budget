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

    <div>
        <button
                {{--v-on:click="show.basicTotals = !show.basicTotals"--}}
                v-on:mouseenter="respondToMouseEnterOnTotalsButton"
                v-on:mouseleave="respondToMouseLeaveOnTotalsButton"
                class="btn btn-default totals-btn"
        >
            Totals
        </button>
    </div>
</div>