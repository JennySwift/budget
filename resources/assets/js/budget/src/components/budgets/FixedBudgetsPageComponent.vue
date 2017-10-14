<template>
    <div id="fixed-budgets-page" class="budgets-page">

        <edit-budget-popup
            :budgets.sync="fixedBudgets"
            page="fixed"
        >
        </edit-budget-popup>

        <budgets-toolbar></budgets-toolbar>

        <new-budget
            :budgets.sync="fixedBudgets"
            page="fixedBudgets"
        >
        </new-budget>

        <div id="budget-content">

            <totals
                :show="show"
            >
            </totals>

            <div class="budget-table fixed-budget-table">
                <fixed-budgets-table></fixed-budgets-table>
            </div>

            <span id="budget_hover_span" class="tooltipster" title=""></span>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                fixedBudgetTotals: [],
                show: store.state.show.defaults,
                shared: store.state,
                orderByOptions: [
                    {name: 'name', value: 'name'},
                    {name: 'spent after starting date', value: 'spentOnOrAfterStartingDate'}
                ],
                orderBy: 'name',
                reverseOrder: false
            };
        },
        components: {},
        computed: {
            fixedBudgets: function () {
                return this.shared.fixedBudgets;
            }
        },
        filters: {
            /**
             *
             * @param number
             * @param howManyDecimals
             * @returns {Number}
             */
            numberFilter: function (number, howManyDecimals) {
                return HelpersRepository.numberFilter(number, howManyDecimals);
            },
            orderBudgetsFilter: function (budgets) {
                return BudgetsRepository.orderBudgetsFilter(budgets, this);
            },
        },
        methods: {
            /**
             *
             */
            respondToMouseEnterOnTotalsButton: function () {
                TotalsRepository.respondToMouseEnterOnTotalsButton(this);
            },

            /**
             *
             */
            respondToMouseLeaveOnTotalsButton: function () {
                TotalsRepository.respondToMouseLeaveOnTotalsButton(this);
            },

            /**
             *
             */
            toggleNewBudget: function () {
                $.event.trigger('toggle-new-budget');
            },

            /**
            *
            */
            getFixedBudgetTotals: function () {
                helpers.get({
                    url: '/api/totals/fixedBudget',
                    storeProperty: 'fixedBudgetTotals',
                    callback: function (response) {
                        this.fixedBudgetTotals = response;
                    }.bind(this)
                });
            },

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
                    that.getFixedBudgetTotals();
                });
            },
        },
        props: [
            //data to be received from parent
        ],
        mounted: function () {
            store.getFixedBudgets();
            this.getFixedBudgetTotals();
            this.listen();
        }
    }
</script>