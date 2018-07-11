<template>
    <div id="fixed-budgets-page" class="budgets-page">

        <budget-popup
            page="fixed"
        >
        </budget-popup>

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
    import helpers from '../../repositories/helpers/Helpers'
    import FixedBudgetsTableComponent from './FixedBudgetsTable.vue'
    import TotalsComponent from '../../components/TotalsComponent.vue'
    import NewBudgetComponent from '../../components/budgets/NewBudgetComponent.vue'
    import BudgetsToolbarComponent from '../../components/shared/BudgetsToolbarComponent.vue'
    import BudgetPopupComponent from '../../components/budgets/BudgetPopupComponent.vue'


    export default {
        data: function () {
            return {
                shared: store.state,
            };
        },
        components: {
            'fixed-budgets-table': FixedBudgetsTableComponent,
            'totals': TotalsComponent,
            'new-budget': NewBudgetComponent,
            'budgets-toolbar': BudgetsToolbarComponent,
            'budget-popup': BudgetPopupComponent

        },
        filters: {

        },
        methods: {
        },
        props: [
            //data to be received from parent
        ],
        mounted: function () {
            store.getFixedBudgets();
            store.getFixedBudgetTotals();
        }
    }
</script>