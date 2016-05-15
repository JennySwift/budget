var UnassignedBudgetsPage = Vue.component('unassigned-budgets-page', {
    template: '#unassigned-budgets-page-template',
    data: function () {
        return {
            show: ShowRepository.defaults,
            unassignedBudgets: []
        };
    },
    components: {},
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
        getUnassignedBudgets: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/budgets?unassigned=true', function (response) {
                    this.unassignedBudgets = response;
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
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getUnassignedBudgets();
    }
});
