<script id="unassigned-budgets-page-template" type="x-template">

<div id="unassigned-budgets-page" class="budgets-page">
    <edit-budget-popup
        :budgets.sync="unassignedBudgets"
    >
    </edit-budget-popup>

    @include('main.budgets.toolbar')

    <new-budget
            :budgets.sync="unassignedBudgets"
            page="unassignedBudgets"
    >
    </new-budget>

    <div id="budget-content">

        <totals
                :show="show"
        >
        </totals>

        <div class="budget-table unassigned-budget-table">
            @include('main.budgets.unassigned-budget-table')
        </div>

        <span id="budget_hover_span" class="tooltipster" title=""></span>
    </div>
</div>

</script>