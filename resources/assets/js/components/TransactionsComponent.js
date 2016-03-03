var Transactions = Vue.component('transactions', {
    template: '#transactions-template',
    data: function () {
        return {
            me: me,
            accounts: [],
            transactions: [],
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
        getTransactions: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/transactions', function (response) {
                this.transactions = response;
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
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.getTransactions();
    }
});

//$rootScope.$on('filterTransactions', function (event, filter) {
//    $scope.showLoading();
//    FilterFactory.getTransactions(FilterFactory.filter)
//        .then(function (response) {
//            $scope.transactions = response.data;
//            $scope.hideLoading();
//        })
//        .catch(function (response) {
//            $scope.responseError(response);
//        })
//});
//
//
//
//$rootScope.$on('handleAllocationForNewTransaction', function (event, $transaction) {
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