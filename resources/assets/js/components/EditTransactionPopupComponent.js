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
            ]
        };
    },
    components: {},
    methods: {

        /**
         *
         */
        updateTransaction: function () {
            $.event.trigger('show-loading');

            var data = TransactionsRepository.setFields(transaction);

            $.event.trigger('clear-total-changes');

            this.$http.put('/api/transactions/' + this.transaction.id, data, function (response) {
                    var index = _.indexOf(this.transactions, _.findWhere(this.transactions, {id: this.transaction.id}));
                    this.transactions[index] = response;
                    $.event.trigger('get-sidebar-totals');
                    $.event.trigger('get-basic-filter-totals');
                    $.event.trigger('run-filter');
                    //this.transactions[index].name = response.name;
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
                $.event.trigger('clear-total-changes');
                $.event.trigger('get-sidebar-totals');
                $.event.trigger('get-basic-filter-totals');
                this.$http.delete('/api/transactions/' + this.selectedTransaction.id, function (response) {
                    this.transactions = _.without(this.transactions, this.selectedTransaction);
                    //var index = _.indexOf(this.transactions, _.findWhere(this.transactions, {id: this.transaction.id}));
                    //this.transactions = _.without(this.transactions, this.transactions[index]);
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
        'accounts'
    ],
    ready: function () {
        this.listen();
    }
});
