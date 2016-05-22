var FixedBudgetsPage = Vue.component('fixed-budgets-page', {
    template: '#fixed-budgets-page-template',
    data: function () {
        return {
            fixedBudgetTotals: [],
            show: ShowRepository.defaults,
            budgetsRepository: BudgetsRepository.state,
            orderByOptions: [
                {name: 'name', value: 'name'},
                {name: 'spent after starting date', value: 'spentAfterStartingDate'}
            ],
            orderBy: 'name',
            reverseOrder: false
        };
    },
    components: {},
    computed: {
        fixedBudgets: function () {
          return this.budgetsRepository.fixedBudgets;
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
            $.event.trigger('show-loading');
            this.$http.get('/api/totals/fixedBudget', function (response) {
                this.fixedBudgetTotals = response;
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
            $(document).on('update-fixed-budget-table-totals', function (event, budget) {
                that.getFixedBudgetTotals();
            });
        },
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        BudgetsRepository.getFixedBudgets(this);
        this.getFixedBudgetTotals();
        this.listen();
    }
});
