<script id="fixed-budgets-page-template" type="x-template">

<div>
    @include('main.budgets.popups.index')

    @include('main.budgets.toolbar')
    @include('main.budgets.new-budget')

    <div id="budget-content">

        @include('main.budgets.totals')

        <div class="budget-table">
            @include('main.budgets.fixed-budget-table')
        </div>

        <span id="budget_hover_span" class="tooltipster" title=""></span>
    </div>
</div>

</script>