<template>
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
</template>

<script>
    export default {
        data: function () {
            return {
                show: ShowRepository.defaults,
                budgetsRepository: BudgetsRepository.state,
                flexBudgetTotals: [],
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
            flexBudgets: function () {
                return this.budgetsRepository.flexBudgets;
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
            getFlexBudgetTotals: function () {
                $.event.trigger('show-loading');
                this.$http.get('/api/totals/flexBudget', function (response) {
                    this.flexBudgetTotals = response;
                    $.event.trigger('hide-loading');
                })
                    .error(function (response) {
                        HelpersRepository.handleResponseError(response);
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
                $(document).on('update-flex-budget-table-totals', function (event) {
                    that.getFlexBudgetTotals();
                });
            },
        },
        props: [
            //data to be received from parent
        ],
        mounted: function () {
            BudgetsRepository.getFlexBudgets(this);
            this.getFlexBudgetTotals();
            this.listen();
        }
    }
</script>