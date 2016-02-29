var EditBudgetPopup = Vue.component('edit-budget-popup', {
    template: '#edit-budget-popup-template',
    data: function () {
        return {
            showPopup: false,
            selectedBudget: {},
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
                name: this.selectedBudget.name
            };

            $.event.trigger('clear-total-changes');

            //$scope.budget_popup.sqlStartingDate = $filter('formatDate')($scope.budget_popup.formattedStartingDate);

            this.$http.put('/api/budgets/' + this.selectedBudget.id, data, function (response) {
                //todo: allow for if budget type is changed. I will have to remove the budget from the table it was in
                var index = _.indexOf(this.budgets, _.findWhere(this.budgets, {id: this.selectedBudget.id}));
                this.budgets[index] = response;
                $.event.trigger('update-budget-table-totals', [response]);
                $.event.trigger('get-sidebar-totals');
                this.showPopup = false;
                //this.budgets[index].name = response.name;
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
        deleteBudget: function () {
            if (confirm('You have ' + this.selectedBudget.transactionsCount + ' transactions with this budget. Are you sure you want to delete it?')) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/budgets/' + this.selectedBudget.id, function (response) {
                    $.event.trigger('get-sidebar-totals');
                    this.budgets = _.without(this.budgets, this.selectedBudget);
                    this.showPopup = false;
                    //var index = _.indexOf(this.budgets, _.findWhere(this.budgets, {id: this.budget.id}));
                    //this.budgets = _.without(this.budgets, this.budgets[index]);
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
        'budgets'
    ],
    ready: function () {
        this.listen();
    }
});
