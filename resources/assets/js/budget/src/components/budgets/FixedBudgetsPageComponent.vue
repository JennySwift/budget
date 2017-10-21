<template>
    <div id="fixed-budgets-page" class="budgets-page">

        <edit-budget-popup
            page="fixed"
        >
        </edit-budget-popup>

        <budgets-toolbar></budgets-toolbar>

        <new-budget
            page="fixedBudgets"
        >
        </new-budget>

        <div id="budget-content">
            <totals></totals>

            <div class="budget-table fixed-budget-table">
                <fixed-budgets-table></fixed-budgets-table>
            </div>

            <span id="budget_hover_span" class="tooltipster" title=""></span>
        </div>
    </div>
</template>

<script>
    import helpers from '../../repositories/Helpers'
    export default {
        data: function () {
            return {
                shared: store.state,
            };
        },
        components: {},
        filters: {

        },
        methods: {

            /**
             *
             * @param budget
             */
            showBudgetPopup: function (budget) {
                $.event.trigger('show-budget-popup', [budget]);
            },

            /**
             *
             */
            listen: function () {
                var that = this;
                $(document).on('update-fixed-budget-table-totals', function (event, budget) {
                    store.getFixedBudgetTotals();
                });
            },
        },
        props: [
            //data to be received from parent
        ],
        mounted: function () {
            store.getFixedBudgets();
            store.getFixedBudgetTotals();
            this.listen();
        }
    }
</script>