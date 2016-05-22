<script id="flex-budgets-page-template" type="x-template">

<div id="flex-budgets-page" class="budgets-page">
    <edit-budget-popup
            :budgets.sync="flexBudgets"
            page="flex"
    >
    </edit-budget-popup>

    @include('main.budgets.toolbar')

    <new-budget
            :budgets.sync="flexBudgets"
            page="flexBudgets"
    >
    </new-budget>

    <div id="budget-content">

        <totals
                :show="show"
        >
        </totals>

        <div class="budget-table flex-budget-table">
            @include('main.budgets.flex-budget-table')
        </div>

        <span id="budget_hover_span" class="tooltipster" title=""></span>
    </div>
</div>

</script>