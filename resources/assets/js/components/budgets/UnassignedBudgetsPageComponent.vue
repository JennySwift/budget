<template>
    <div id="unassigned-budgets-page" class="budgets-page">

        <budget-popup
            page="unassigned"
        >
        </budget-popup>

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
    import helpers from '../../repositories/Helpers'
    import TotalsComponent from '../../components/TotalsComponent.vue'
    import NewBudgetComponent from '../../components/budgets/NewBudgetComponent.vue'
    import BudgetsToolbarComponent from '../../components/shared/BudgetsToolbarComponent.vue'
    import BudgetPopupComponent from '../../components/budgets/BudgetPopupComponent.vue'


    export default {
        data: function () {
            return {
                shared: store.state
            };
        },
        components: {
            'totals': TotalsComponent,
            'new-budget': NewBudgetComponent,
            'budgets-toolbar': BudgetsToolbarComponent,
            'budget-popup': BudgetPopupComponent
        },
        computed: {
            orderedUnassignedBudgets: function () {
                return _.orderBy(this.shared.unassignedBudgets, 'name');
            }
        },
        methods: {

            /**
             *
             * @param budget
             */
            showBudgetPopup: function (budget) {
                store.set(budget, 'selectedBudget');
                helpers.showPopup('budget');
            },
        },
        props: [
            //data to be received from parent
        ],
        mounted: function () {

        }
    }
</script>
