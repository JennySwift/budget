var Totals = Vue.component('totals', {
    template: '#totals-template',
    data: function () {
        return {
            totalChanges: TotalsRepository.resetTotalChanges(),
            sideBarTotals: [],
            totalsLoading: false,
            me: me
        };
    },
    components: {},
    watch: {
        'sideBarTotals': function (newValue, oldValue) {
            //Just checking it's not on page load with this if check, otherwise values will be NaN
            if (newValue && (oldValue.remainingBalance || oldValue.remainingBalance === 0)) {

                if (newValue.credit !== oldValue.credit) {
                    this.totalChanges.credit = this.calculateDifference(newValue.credit, oldValue.credit);
                }

                if (newValue.debit !== oldValue.debit) {
                    this.totalChanges.debit = this.calculateDifference(newValue.debit, oldValue.debit);
                }

                if (newValue.balance !== oldValue.balance) {
                    this.totalChanges.balance = this.calculateDifference(newValue.balance, oldValue.balance);
                }

                if (newValue.reconciledSum !== oldValue.reconciledSum) {
                    this.totalChanges.reconciledSum = this.calculateDifference(newValue.reconciledSum, oldValue.reconciledSum);
                }

                if (newValue.savings !== oldValue.savings) {
                    this.totalChanges.savings = this.calculateDifference(newValue.savings, oldValue.savings);
                }

                if (newValue.expensesWithoutBudget !== oldValue.expensesWithoutBudget) {
                    this.totalChanges.expensesWithoutBudget = this.calculateDifference(newValue.expensesWithoutBudget, oldValue.expensesWithoutBudget);
                }

                if (newValue.remainingFixedBudget !== oldValue.remainingFixedBudget) {
                    this.totalChanges.remainingFixedBudget = this.calculateDifference(newValue.remainingFixedBudget, oldValue.remainingFixedBudget);
                }

                if (newValue.cumulativeFixedBudget !== oldValue.cumulativeFixedBudget) {
                    this.totalChanges.cumulativeFixedBudget = this.calculateDifference(newValue.cumulativeFixedBudget, oldValue.cumulativeFixedBudget);
                }

                if (newValue.expensesWithFixedBudgetBeforeStartingDate !== oldValue.expensesWithFixedBudgetBeforeStartingDate) {
                    this.totalChanges.expensesWithFixedBudgetBeforeStartingDate = this.calculateDifference(newValue.expensesWithFixedBudgetBeforeStartingDate, oldValue.expensesWithFixedBudgetBeforeStartingDate);
                }

                if (newValue.expensesWithFixedBudgetAfterStartingDate !== oldValue.expensesWithFixedBudgetAfterStartingDate) {
                    this.totalChanges.expensesWithFixedBudgetAfterStartingDate = this.calculateDifference(newValue.expensesWithFixedBudgetAfterStartingDate, oldValue.expensesWithFixedBudgetAfterStartingDate);
                }

                if (newValue.expensesWithFlexBudgetBeforeStartingDate !== oldValue.expensesWithFlexBudgetBeforeStartingDate) {
                    this.totalChanges.expensesWithFlexBudgetBeforeStartingDate = this.calculateDifference(newValue.expensesWithFlexBudgetBeforeStartingDate, oldValue.expensesWithFlexBudgetBeforeStartingDate);
                }

                if (newValue.expensesWithFlexBudgetAfterStartingDate !== oldValue.expensesWithFlexBudgetAfterStartingDate) {
                    this.totalChanges.expensesWithFlexBudgetAfterStartingDate = this.calculateDifference(newValue.expensesWithFlexBudgetAfterStartingDate, oldValue.expensesWithFlexBudgetAfterStartingDate);
                }

                if (newValue.remainingBalance !== oldValue.remainingBalance) {
                    this.totalChanges.remainingBalance = this.calculateDifference(newValue.remainingBalance, oldValue.remainingBalance);
                }

                this.sideBarTotals = newValue;
            }
        }
    },
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
        *
        */
        getSideBarTotals: function () {
            this.totalsLoading = true;
            this.$http.get('/api/totals/sidebar', function (response) {
                this.sideBarTotals = response.data;
                this.totalsLoading = false;
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         * @param newValue
         * @param oldValue
         * @returns {string}
         */
        calculateDifference: function (newValue, oldValue) {
            var diff = newValue - oldValue;
            return diff.toFixed(2);
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
                that.totalChanges = TotalsRepository.resetTotalChanges();
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
