var Transactions = Vue.component('transactions', {
    template: '#transactions-template',
    data: function () {
        return {
            me: me,
            accounts: [],
            showStatus: false,
            showDate: true,
            showDescription: true,
            showMerchant: true,
            showTotal: true,
            showType: true,
            showAccount: true,
            showDuration: true,
            showReconciled: true,
            showAllocated: true,
            showBudgets: true,
            showDelete: true,
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        getAccounts: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/accounts', function (response) {
                this.accounts = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
        *
        */
        filterTransactions: function (newTransaction) {
            $.event.trigger('show-loading');

            var data = {
                filter: FilterRepository.formatDates(this.filter)
            };

            this.$http.post('/api/filter/transactions', data, function (response) {
                this.transactions = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         */
        listen: function () {
            var that = this;

            $(document).on('filter-transactions', function (event, filter) {
                if (filter) {
                    that.filter = filter;
                }
                that.filterTransactions();
            });
            
            //$(document).on('update-new-transaction-allocation-in-js', function (event, transaction) {
            //    //Find the transaction in the JS, if it is there
            //    var index = _.indexOf(this.transactions, _.findWhere(this.transactions, {id: transaction.id}));
            //    //Update the transaction if it is there on the page
            //    if (index) {
            //        this.transactions[index].allocated = transaction.allocated;
            //    }
            //});
        }
    },
    props: [
        'transactions',
        'transactionPropertiesToShow'
    ],
    ready: function () {
        this.getAccounts();
        this.listen();
    }
});