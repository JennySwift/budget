var Totals = Vue.component('totals', {
    template: '#totals-template',
    data: function () {
        return {
            totalChanges: {},
            sideBarTotals: [],
            totalsLoading: false
        };
    },
    components: {},
    filters: {
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
    methods: {
        /**
         * Get all the totals
         * @returns {*}
         */
        //getTotals: function () {
        //    var $url = '/api/totals';
        //
        //    return $http.get($url);
        //},

        /**
        *
        */
        getSideBarTotals: function () {
            //$.event.trigger('show-loading');
            this.totalsLoading = true;
            this.$http.get('/api/totals/sidebar', function (response) {
                this.sideBarTotals = response.data;
                //$.event.trigger('hide-loading');
                this.totalsLoading = false;
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        getTotals: function () {
            var $url = '/api/totals/sidebar';

            return $http.get($url);
        },
        getFixedBudgetTotals: function () {
            var $url = '/api/totals/fixedBudget';

            return $http.get($url);
        },
        getFlexBudgetTotals: function () {
            var $url = '/api/totals/flexBudget';

            return $http.get($url);
        },
        getUnassignedBudgetTotals: function () {
            var $url = '/api/totals/unassignedBudget';

            return $http.get($url);
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('get-sidebar-totals', function (event) {
                that.getSideBarTotals();
            });
            $(document).on('clear-total-changes', function (event) {
                that.totalChanges = {};
            });
        }
    },
    props: [
        'show'
    ],
    ready: function () {
        this.getSideBarTotals();
        this.listen();
    }
});

//$scope.$watch('sideBarTotals', function (newValue, oldValue, scope) {
//
//    if (newValue && oldValue) {
//
//        if (newValue.credit !== oldValue.credit) {
//            $scope.totalChanges.credit = $scope.calculateDifference(newValue.credit, oldValue.credit);
//        }
//
//        if (newValue.debit !== oldValue.debit) {
//            $scope.totalChanges.debit = $scope.calculateDifference(newValue.debit, oldValue.debit);
//        }
//
//        if (newValue.balance !== oldValue.balance) {
//            $scope.totalChanges.balance = $scope.calculateDifference(newValue.balance, oldValue.balance);
//        }
//
//        if (newValue.reconciledSum !== oldValue.reconciledSum) {
//            $scope.totalChanges.reconciledSum = $scope.calculateDifference(newValue.reconciledSum, oldValue.reconciledSum);
//        }
//
//        if (newValue.savings !== oldValue.savings) {
//            $scope.totalChanges.savings = $scope.calculateDifference(newValue.savings, oldValue.savings);
//        }
//
//        if (newValue.expensesWithoutBudget !== oldValue.expensesWithoutBudget) {
//            $scope.totalChanges.expensesWithoutBudget = $scope.calculateDifference(newValue.expensesWithoutBudget, oldValue.expensesWithoutBudget);
//        }
//
//        if (newValue.remainingFixedBudget !== oldValue.remainingFixedBudget) {
//            $scope.totalChanges.remainingFixedBudget = $scope.calculateDifference(newValue.remainingFixedBudget, oldValue.remainingFixedBudget);
//        }
//
//        if (newValue.cumulativeFixedBudget !== oldValue.cumulativeFixedBudget) {
//            $scope.totalChanges.cumulativeFixedBudget = $scope.calculateDifference(newValue.cumulativeFixedBudget, oldValue.cumulativeFixedBudget);
//        }
//
//        if (newValue.expensesWithFixedBudgetBeforeStartingDate !== oldValue.expensesWithFixedBudgetBeforeStartingDate) {
//            $scope.totalChanges.expensesWithFixedBudgetBeforeStartingDate = $scope.calculateDifference(newValue.expensesWithFixedBudgetBeforeStartingDate, oldValue.expensesWithFixedBudgetBeforeStartingDate);
//        }
//
//        if (newValue.expensesWithFixedBudgetAfterStartingDate !== oldValue.expensesWithFixedBudgetAfterStartingDate) {
//            $scope.totalChanges.expensesWithFixedBudgetAfterStartingDate = $scope.calculateDifference(newValue.expensesWithFixedBudgetAfterStartingDate, oldValue.expensesWithFixedBudgetAfterStartingDate);
//        }
//
//        if (newValue.expensesWithFlexBudgetBeforeStartingDate !== oldValue.expensesWithFlexBudgetBeforeStartingDate) {
//            $scope.totalChanges.expensesWithFlexBudgetBeforeStartingDate = $scope.calculateDifference(newValue.expensesWithFlexBudgetBeforeStartingDate, oldValue.expensesWithFlexBudgetBeforeStartingDate);
//        }
//
//        if (newValue.expensesWithFlexBudgetAfterStartingDate !== oldValue.expensesWithFlexBudgetAfterStartingDate) {
//            $scope.totalChanges.expensesWithFlexBudgetAfterStartingDate = $scope.calculateDifference(newValue.expensesWithFlexBudgetAfterStartingDate, oldValue.expensesWithFlexBudgetAfterStartingDate);
//        }
//
//        if (newValue.remainingBalance !== oldValue.remainingBalance) {
//            $scope.totalChanges.remainingBalance = $scope.calculateDifference(newValue.remainingBalance, oldValue.remainingBalance);
//        }
//
//        scope.sideBarTotals = newValue;
//    }
//});
//
///**
// * End watches
// */
//
///**
// * @param newValue
// * @param oldValue
// * @returns {string}
// */
//$scope.calculateDifference = function (newValue, oldValue) {
//    var $diff = newValue - oldValue;
//    return $diff.toFixed(2);
//};
//
//$scope.showSavingsTotalInput = function () {
//    $scope.show.savings_total.input = true;
//    $scope.show.savings_total.edit_btn = false;
//};
//
