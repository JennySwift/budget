var UnassignedBudgetsPage = Vue.component('unassigned-budgets-page', {
    template: '#unassigned-budgets-page-template',
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
    ready: function () {
        
    }
});
