var FlexBudgetsPage = Vue.component('flex-budgets-page', {
    template: '#flex-budgets-page-template',
    data: function () {
        return {
            show: ShowRepository.defaults,
            budgetsRepository: BudgetsRepository.state,
            flexBudgetTotals: [],
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
    ready: function () {
        BudgetsRepository.getFlexBudgets(this);
        this.getFlexBudgetTotals();
        this.listen();
    }

});
