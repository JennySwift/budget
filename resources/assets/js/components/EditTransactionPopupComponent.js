var EditTransactionPopup = Vue.component('edit-transaction-popup', {
    template: '#edit-transaction-popup-template',
    data: function () {
        return {
            showPopup: false,
            selectedTransaction: {},
            types: [
                {value: 'income', name: 'credit'},
                {value: 'expense', name: 'debit'},
                {value: 'transfer', name: 'transfer'},
            ],
            budgetsRepository: BudgetsRepository.state
        };
    },
    components: {},
    computed: {
        budgets: function () {
          return this.budgetsRepository.budgets;
        }
    },
    methods: {

        /**
         *
         */
        updateTransaction: function () {
            $.event.trigger('show-loading');

            var data = TransactionsRepository.setFields(this.selectedTransaction);

            $.event.trigger('clear-total-changes');

            this.$http.put('/api/transactions/' + this.selectedTransaction.id, data, function (response) {
                TransactionsRepository.updateTransaction(response);
                $.event.trigger('get-sidebar-totals');
                FilterRepository.getBasicFilterTotals(this);
                FilterRepository.runFilter(this);
                this.showPopup = false;
                //Todo: Remove the transaction from the JS transactions depending on the filter
                $.event.trigger('provide-feedback', ['Transaction updated', 'success']);
                $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
        },
        
        /**
        *
        */
        deleteTransaction: function () {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/transactions/' + this.selectedTransaction.id, function (response) {
                    TransactionsRepository.deleteTransaction(this.selectedTransaction);
                    $.event.trigger('clear-total-changes');
                    $.event.trigger('get-sidebar-totals');
                    FilterRepository.getBasicFilterTotals(this);
                    this.showPopup = false;
                    $.event.trigger('provide-feedback', ['Transaction deleted', 'success']);
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
            $(document).on('show-edit-transaction-popup', function (event, transaction) {
                that.selectedTransaction = transaction;

                //save the original total so I can calculate
                // the difference if the total changes,
                // so I can remove the correct amount from savings if required.
                that.selectedTransaction.originalTotal = that.selectedTransaction.total;
                that.selectedTransaction.duration = HelpersRepository.formatDurationToMinutes(that.selectedTransaction.minutes);

                that.showPopup = true;
            });
        }
    },
    props: [
        'accounts',
        'transactions'
    ],
    ready: function () {
        this.listen();
    }
});
