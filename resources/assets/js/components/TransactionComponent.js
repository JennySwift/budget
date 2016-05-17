var Transaction = Vue.component('transaction', {
    template: '#transaction-template',
    data: function () {
        return {

        };
    },
    components: {},
    filters: {
        /**
         *
         * @param minutes
         * @returns {*}
         */
        formatDurationFilter: function (minutes) {
            return HelpersRepository.formatDurationToHoursAndMinutes(minutes);
        },

        /**
         *
         * @param number
         * @param howManyDecimals
         * @returns {Number}
         */
        numberFilter: function (number, howManyDecimals) {
            return HelpersRepository.numberFilter(number, howManyDecimals);
        },

        /**
         *
         * @param date
         * @returns {*|String}
         */
        formatDateForUser: function (date) {
            return HelpersRepository.formatDateForUser(date, me.preferences.dateFormat);
        }
    },
    methods: {

        /**
        * I think this is just for the reconciled checkbox.
         * For the updating of a transaction from the popup, see EditTransactionPopupComponent
        */
        updateTransaction: function () {
            $.event.trigger('show-loading');

            var data = TransactionsRepository.setFields(this.transaction);
            
            $.event.trigger('clear-total-changes');

            this.$http.put('/api/transactions/' + this.transaction.id, data, function (response) {
                TransactionsRepository.updateTransaction(this.transaction);
                $.event.trigger('get-sidebar-totals');
                FilterRepository.getBasicFilterTotals(this);
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
         * @param transaction
         */
        showAllocationPopup: function (transaction) {
            $.event.trigger('show-allocation-popup', [transaction]);
        },

        /**
         *
         * @param transaction
         */
        showEditTransactionPopup: function (transaction) {
            $.event.trigger('show-edit-transaction-popup', [transaction]);
        }

    },
    props: [
        'transactions',
        'transaction',
        'transactionPropertiesToShow'
    ],
    ready: function () {
        //this.listen();
    }
});
