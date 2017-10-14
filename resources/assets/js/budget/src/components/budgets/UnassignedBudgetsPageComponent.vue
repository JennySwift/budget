<template>
    <div id="unassigned-budgets-page" class="budgets-page">
        <edit-budget-popup
            :budgets.sync="unassignedBudgets"
            page="unassigned"
        >
        </edit-budget-popup>

        <budgets-toolbar></budgets-toolbar>

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

                <h1>Unassigned Budget Table</h1>

                <table id="unassigned-budget-info-table" class="table table-bordered">

                    <tr>
                        <th>Name</th>
                    </tr>

                    <!-- table content -->
                    <tr v-for="budget in unassignedBudgets" class="budget_info_ul">
                        <td v-on:click="showBudgetPopup(budget, 'unassigned')" class="pointer">{{ budget.name }}</td>
                    </tr>

                </table>
            </div>

            <span id="budget_hover_span" class="tooltipster" title=""></span>
        </div>
    </div>
</template>

<script>
    import TotalsRepository from '../../repositories/TotalsRepository'
    export default {
        data: function () {
            return {
                show: ShowRepository.defaults,
                shared: store.state
            };
        },
        components: {},
        computed: {
            unassignedBudgets: function () {
                return _.orderBy(this.shared.unassignedBudgets, 'name');
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
