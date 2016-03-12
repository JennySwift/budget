var EditBudgetPopup = Vue.component('edit-budget-popup', {
    template: '#edit-budget-popup-template',
    data: function () {
        return {
            showPopup: false,
            selectedBudget: {},
            types: ['fixed', 'flex', 'unassigned']
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        updateBudget: function (budget) {
            $.event.trigger('show-loading');

            var data = {
                name: this.selectedBudget.name,
                amount: this.selectedBudget.amount,
                type: this.selectedBudget.type,
                starting_date: HelpersRepository.formatDate(this.selectedBudget.formattedStartingDate),
            };

            $.event.trigger('clear-total-changes');

            this.$http.put('/api/budgets/' + this.selectedBudget.id, data, function (response) {
                this.jsUpdateBudget(response);
                this.updateBudgetTableTotals();
                $.event.trigger('get-sidebar-totals');
                this.showPopup = false;
                $.event.trigger('provide-feedback', ['Budget updated', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         * todo: allow for if budget type is changed. I will have to remove the budget from the table it was in
         * @param budget
         */
        jsUpdateBudget: function (budget) {
            var index = _.indexOf(this.budgets, _.findWhere(this.budgets, {id: this.selectedBudget.id}));

            if (this.page !== budget.type) {
                //Remove the budget from the table
                this.budgets = _.without(this.budgets, this.budgets[index]);
            }
            else {
                //Update the budget with the JS
                this.budgets[index].name = budget.name;
                this.budgets[index].amount = budget.amount;
                this.budgets[index].calculatedAmount = budget.calculatedAmount;
                this.budgets[index].cumulative = budget.cumulative;
                this.budgets[index].cumulativeMonthNumber = budget.cumulativeMonthNumber;
                this.budgets[index].formattedStartingDate = budget.formattedStartingDate;
                this.budgets[index].path = budget.path;
                this.budgets[index].received = budget.received;
                this.budgets[index].receivedAfterStartingDate = budget.receivedAfterStartingDate;
                this.budgets[index].remaining = budget.remaining;
                this.budgets[index].spent = budget.spent;
                this.budgets[index].spentAfterStartingDate = budget.spentAfterStartingDate;
                this.budgets[index].spentBeforeStartingDate = budget.spentBeforeStartingDate;
                this.budgets[index].transactionsCount = budget.transactionsCount;
                this.budgets[index].type = budget.type;
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
        deleteBudget: function () {
            if (confirm('You have ' + this.selectedBudget.transactionsCount + ' transactions with this budget. Are you sure you want to delete it?')) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/budgets/' + this.selectedBudget.id, function (response) {
                    $.event.trigger('get-sidebar-totals');
                    this.updateBudgetTableTotals();
                    this.budgets = _.without(this.budgets, this.selectedBudget);
                    this.showPopup = false;
                    $.event.trigger('provide-feedback', ['Budget deleted', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
            }
        },

        /**
         *
         */
        closePopup: function ($event) {
            HelpersRepository.closePopup($event, this);
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-budget-popup', function (event, budget) {
                that.selectedBudget = budget;
                that.showPopup = true;
            });
        }
    },
    props: [
        'budgets',
        'page'
    ],
    ready: function () {
        this.listen();
    }
});
