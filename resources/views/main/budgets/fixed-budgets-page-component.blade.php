<script id="fixed-budgets-page-template" type="x-template">

<div>
    <edit-budget-popup></edit-budget-popup>

    @include('main.budgets.toolbar')
    <new-budget></new-budget>

    <div id="budget-content">

        <totals></totals>

        <div class="budget-table">
            @include('main.budgets.fixed-budget-table')
        </div>

        <span id="budget_hover_span" class="tooltipster" title=""></span>
    </div>
</div>

</script>