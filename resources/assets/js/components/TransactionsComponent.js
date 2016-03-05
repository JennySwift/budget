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
         * @param transaction
         */
        showAllocationPopup: function (transaction) {
            $.event.trigger('show-allocation-popup', [transaction]);
        },

        /**
        *
        */
        filterTransactions: function () {
            $.event.trigger('show-loading');

            var filter = FilterRepository.formatDates(FilterRepository.filter);

            this.$http.post('/api/filter/transactions', filter, function (response) {
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
                that.filterTransactions();
            });
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


//
//
//
//$rootScope.$on('transaction-created-with-multiple-budgets', function (event, $transaction) {
//    FilterFactory.getTransactions(FilterFactory.filter)
//        .then(function (response) {
//            $scope.hideLoading();
//            $scope.transactions = response.data;
//            var $index = _.indexOf($scope.transactions, _.findWhere($scope.transactions, {id: $transaction.id}));
//            if ($index !== -1) {
//                //The transaction that was just entered is in the filtered transactions
//                $scope.showAllocationPopup($scope.transactions[$index]);
//                //$scope.transactions[$index] = $scope.allocationPopup;
//            }
//            else {
//                $scope.showAllocationPopup($transaction);
//            }
//        })
//        .catch(function (response) {
//            $scope.responseError(response);
//        })
//});