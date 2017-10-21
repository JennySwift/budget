<template>
    <div id="unassigned-budgets-page" class="budgets-page">

        <edit-budget-popup
            page="unassigned"
        >
        </edit-budget-popup>

        <budgets-toolbar></budgets-toolbar>

        <new-budget
            page="unassignedBudgets"
        >
        </new-budget>

        <div id="budget-content">

            <totals></totals>

            <div class="budget-table unassigned-budget-table">

                <h1>Unassigned Budget Table</h1>

                <table id="unassigned-budget-info-table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="budget in orderedUnassignedBudgets" class="budget_info_ul">
                            <td v-on:click="showBudgetPopup(budget, 'unassigned')" class="pointer">{{ budget.name }}</td>
                        </tr>
                    </tbody>
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
                shared: store.state
            };
        },
        components: {},
        computed: {
            orderedUnassignedBudgets: function () {
                return _.orderBy(this.shared.unassignedBudgets, 'name');
            }
        },
        methods: {

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
