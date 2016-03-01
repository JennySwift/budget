var NewBudget = Vue.component('new-budget', {
    template: '#new-budget-template',
    data: function () {
        return {
            showNewBudget: false,
            newBudget: {},
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

            $.event.trigger('clear-total-changes');

            this.$http.post('/api/budgets', data, function (response) {
                this.jsInsertBudget(response);
                $.event.trigger('get-sidebar-totals');
                this.updateBudgetTableTotals();
                $.event.trigger('provide-feedback', ['Budget created', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (data, status, response) {
                HelpersRepository.handleResponseError(data, status, response);
            });
        },

        /**
         * If the type of the new budget created matches the budget page the user is on, add it to the budgets on that page
         */
        jsInsertBudget: function (response) {
            if (this.page === 'fixedBudgets' && this.newBudget.type === 'fixed') {
                this.budgets.push(response.data);
            }
            else if (this.page === 'flexBudgets' && this.newBudget.type === 'flex') {
                this.budgets.push(response.data);
            }
            else if (this.page === 'unassignedBudgets' && this.newBudget.type === 'unassigned') {
                this.budgets.push(response.data);
            }
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
