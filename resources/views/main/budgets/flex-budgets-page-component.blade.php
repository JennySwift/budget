<script id="flex-budgets-page-template" type="x-template">

<div>
    <edit-budget-popup
            :budgets.sync="flexBudgets"
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
            @include('main.budgets.flex-budget-table')
        </div>

        <span id="budget_hover_span" class="tooltipster" title=""></span>
    </div>
</div>

</script>