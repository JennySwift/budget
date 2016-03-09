var MassTransactionUpdatePopup = Vue.component('mass-transaction-update-popup', {
    template: '#mass-transaction-update-popup-template',
    data: function () {
        return {
            showPopup: false,
            budgetsToAdd: [],
            count: 0
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        addBudgetsToTransactions: function () {
            this.count = 0;
            for (var i = 0; i < this.transactions.length; i++) {
                this.addBudgetsToTransaction(this.transactions[i]);
            }
        },

        /**
         *
         * @param transaction
         */
        addBudgetsToTransaction: function (transaction) {
            $.event.trigger('show-loading');

            var data = {
                addingBudgets: true,
                budget_ids: _.pluck(this.budgetsToAdd, 'id')
            };

            this.$http.put('/api/transactions/' + transaction.id, data, function (response) {
                var index = _.indexOf(this.transactions, _.findWhere(this.transactions, {id: transaction.id}));
                this.transactions[index].budgets = response.budgets;
                this.transactions[index].multipleBudgets = response.multipleBudgets;
                this.count++;

                if (this.count === this.transactions.length) {
                    $.event.trigger('provide-feedback', ['Done!', 'success']);
                    this.showPopup = false;
                }

                //$.event.trigger('provide-feedback', ['Transaction updated', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        closePopup: function (event) {
            HelpersRepository.closePopup(event, this);
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-mass-transaction-update-popup', function (event) {
                that.showPopup = true;
            });
        }
    },
    props: [
        'transactions',
        'budgets'
    ],
    ready: function () {
        this.listen();
    }
});
