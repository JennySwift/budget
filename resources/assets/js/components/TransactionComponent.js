var Transaction = Vue.component('transaction', {
    template: '#transaction-template',
    data: function () {
        return {

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
         * @param transaction
         */
        showEditTransactionPopup: function (transaction) {
            $.event.trigger('show-edit-transaction-popup', [transaction]);
        },

    },
    filters: {
        /**
         *
         * @param minutes
         * @returns {*}
         */
        formatDurationFilter: function (minutes) {
            if (minutes) {
                return '';
            }

            var moment = moment.duration(minutes, 'minutes');
            var formattedDuration = moment._data.hours + ':' + moment._data.minutes;

            return formattedDuration;
        },

        /**
         *
         * @param number
         * @param howManyDecimals
         * @returns {Number}
         */
        numberFilter: function (number, howManyDecimals) {
            return HelpersRepository.numberFilter(number, howManyDecimals);
        }
    },
    props: [
        'transaction',
        'showStatus',
        'showDate',
        'showDescription',
        'showMerchant',
        'showTotal',
        'showType',
        'showAccount',
        'showDuration',
        'showReconciled',
        'showAllocated',
        'showBudgets',
        'showDelete',
    ],
    ready: function () {

    }
});
