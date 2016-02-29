<script id="fixed-budgets-page-template" type="x-template">

<div>
    <edit-budget-popup
            :budgets.sync="fixedBudgets"
            page="fixedBudgets"
    >
    </edit-budget-popup>

    @include('main.budgets.toolbar')

    <new-budget></new-budget>

    <div id="budget-content">

        <totals
            :show="show"
        >
        </totals>

        <div class="budget-table">
            @include('main.budgets.fixed-budget-table')
        </div>

        <span id="budget_hover_span" class="tooltipster" title=""></span>
    </div>
</div>

</script>