var NewBudget = Vue.component('new-budget', {
    template: '#new-budget-template',
    data: function () {
        return {
            showNewBudget: false,
            newBudget: {
                type: 'fixed'
            },
            types: ['fixed', 'flex', 'unassigned']
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        insertBudget: function () {
            $.event.trigger('show-loading');
            var data = {
                name: this.newBudget.name,
                type: this.newBudget.type,
                amount: this.newBudget.amount,
                starting_date: HelpersRepository.formatDate(this.newBudget.startingDate),
            };

            TotalsRepository.resetTotalChanges();

            this.$http.post('/api/budgets', data, function (response) {
                BudgetsRepository.addBudgetToSpecificArray(response, this);
                TotalsRepository.getSideBarTotals(this);
                this.updateBudgetTableTotals();
                $.event.trigger('provide-feedback', ['Budget created', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (data, status, response) {
                HelpersRepository.handleResponseError(data, status, response);
            });
        },

        /**
         *
         */
        updateBudgetTableTotals: function () {
            if (this.page == 'fixedBudgets') {
                $.event.trigger('update-fixed-budget-table-totals');
            }
            else if (this.page == 'flexBudgets') {
                $.event.trigger('update-flex-budget-table-totals');
            }
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('toggle-new-budget', function (event) {
                that.showNewBudget = !that.showNewBudget;
            });
        }
    },
    props: [
        'page',
        'budgets'
    ],
    ready: function () {
        this.listen();
    }
});
