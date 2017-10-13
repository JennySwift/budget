<template>
    <div id="unassigned-budgets-page" class="budgets-page">
        <edit-budget-popup
            :budgets.sync="unassignedBudgets"
            page="unassigned"
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
</template>

<script>
    export default {
        data: function () {
            return {
                show: ShowRepository.defaults,
                budgetsRepository: BudgetsRepository.state
            };
        },
        components: {},
        computed: {
            unassignedBudgets: function () {
                return this.budgetsRepository.unassignedBudgets;
            }
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
             * @param budget
             */
            showBudgetPopup: function (budget) {
                $.event.trigger('show-budget-popup', [budget]);
            },
        },
        props: [
            //data to be received from parent
        ],
        mounted: function () {

        }
    }
</script>
