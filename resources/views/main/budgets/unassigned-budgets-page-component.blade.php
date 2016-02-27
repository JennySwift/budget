<script id="unassigned-budgets-page-template" type="x-template">

<div>
    @include('main.budgets.edit-budget-popup-component')

    @include('main.budgets.toolbar')
    @include('main.budgets.new-budget-component')

    <div id="budget-content">

        @include('main.budgets.totals')

        <div class="budget-table">
            @include('main.budgets.unassigned-budget-table')
        </div>

        <span id="budget_hover_span" class="tooltipster" title=""></span>
    </div>
</div>

</script>