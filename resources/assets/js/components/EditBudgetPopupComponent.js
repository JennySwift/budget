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

            TotalsRepository.resetTotalChanges();

            this.$http.put('/api/budgets/' + this.selectedBudget.id, data, function (response) {
                BudgetsRepository.updateBudget(response, this);
                this.updateBudgetTableTotals();
                TotalsRepository.getSideBarTotals(this);
                this.showPopup = false;
                $.event.trigger('provide-feedback', ['Budget updated', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        updateBudgetTableTotals: function () {
            if (this.page == 'fixed') {
                $.event.trigger('update-fixed-budget-table-totals');
            }
            else if (this.page == 'flex') {
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
                    TotalsRepository.getSideBarTotals(this);
                    this.updateBudgetTableTotals();
                    BudgetsRepository.deleteBudget(this.selectedBudget, this);
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
