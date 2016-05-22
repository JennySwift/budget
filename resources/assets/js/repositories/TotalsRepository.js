var TotalsRepository = {

    state: {
        sideBarTotals: {
            remainingBalance: '',
            remainingFixedBudget: '',
            cumulativeFixedBudget: '',
            credit: '',
            debit: '',
            balance: '',
            reconciledSum: '',
            expensesWithoutBudget: '',
            savings: '',
            expensesWithFixedBudgetBeforeStartingDate: '',
            expensesWithFixedBudgetAfterStartingDate: '',
            expensesWithFlexBudgetBeforeStartingDate: '',
            expensesWithFlexBudgetAfterStartingDate: ''
        },
        totalChanges: {
            remainingBalance: 0,
            remainingFixedBudget: 0,
            cumulativeFixedBudget: 0,
            credit: 0,
            debit: 0,
            balance: 0,
            reconciledSum: 0,
            expensesWithoutBudget: 0,
            savings: 0,
            expensesWithFixedBudgetBeforeStartingDate: 0,
            expensesWithFixedBudgetAfterStartingDate: 0,
            expensesWithFlexBudgetBeforeStartingDate: 0,
            expensesWithFlexBudgetAfterStartingDate: 0,
        },
    },

    /**
     *
     */
    getSideBarTotals: function (that) {
        that.totalsLoading = true;
        var oldSideBarTotals = this.state.sideBarTotals;
        that.$http.get('/api/totals/sidebar', function (response) {
            TotalsRepository.state.sideBarTotals = response.data;
            TotalsRepository.setTotalChanges(oldSideBarTotals);
            that.totalsLoading = false;
        })
        .error(function (response) {
            HelpersRepository.handleResponseError(response);
        });
    },

    /**
     *
     * @returns {{remainingBalance: number, remainingFixedBudget: number, cumulativeFixedBudget: number, credit: number, debit: number, balance: number, reconciledSum: number, expensesWithoutBudget: number, savings: number, expensesWithFixedBudgetBeforeStartingDate: number, expensesWithFixedBudgetAfterStartingDate: number, expensesWithFlexBudgetBeforeStartingDate: number, expensesWithFlexBudgetAfterStartingDate: number}}
     */
    resetTotalChanges: function () {
        var that = this;
        $.each(this.state.totalChanges, function (key, value) {
            that.state.totalChanges[key] = 0;
        });
    },

    /**
     *
     * @param oldSideBarTotals
     */
    setTotalChanges: function (oldSideBarTotals) {
        if (oldSideBarTotals.remainingBalance === '') {
            //Totals were just loaded for the first time, not changing
            return false;
        }
        var newSideBarTotals = this.state.sideBarTotals;

        if (newSideBarTotals.credit !== oldSideBarTotals.credit) {
            this.state.totalChanges.credit = this.calculateDifference(newSideBarTotals.credit, oldSideBarTotals.credit);
        }

        if (newSideBarTotals.debit !== oldSideBarTotals.debit) {
            this.state.totalChanges.debit = this.calculateDifference(newSideBarTotals.debit, oldSideBarTotals.debit);
        }

        if (newSideBarTotals.balance !== oldSideBarTotals.balance) {
            this.state.totalChanges.balance = this.calculateDifference(newSideBarTotals.balance, oldSideBarTotals.balance);
        }

        if (newSideBarTotals.reconciledSum !== oldSideBarTotals.reconciledSum) {
            this.state.totalChanges.reconciledSum = this.calculateDifference(newSideBarTotals.reconciledSum, oldSideBarTotals.reconciledSum);
        }

        if (newSideBarTotals.savings !== oldSideBarTotals.savings) {
            this.state.totalChanges.savings = this.calculateDifference(newSideBarTotals.savings, oldSideBarTotals.savings);
        }

        if (newSideBarTotals.expensesWithoutBudget !== oldSideBarTotals.expensesWithoutBudget) {
            this.state.totalChanges.expensesWithoutBudget = this.calculateDifference(newSideBarTotals.expensesWithoutBudget, oldSideBarTotals.expensesWithoutBudget);
        }

        if (newSideBarTotals.remainingFixedBudget !== oldSideBarTotals.remainingFixedBudget) {
            this.state.totalChanges.remainingFixedBudget = this.calculateDifference(newSideBarTotals.remainingFixedBudget, oldSideBarTotals.remainingFixedBudget);
        }

        if (newSideBarTotals.cumulativeFixedBudget !== oldSideBarTotals.cumulativeFixedBudget) {
            this.state.totalChanges.cumulativeFixedBudget = this.calculateDifference(newSideBarTotals.cumulativeFixedBudget, oldSideBarTotals.cumulativeFixedBudget);
        }

        if (newSideBarTotals.expensesWithFixedBudgetBeforeStartingDate !== oldSideBarTotals.expensesWithFixedBudgetBeforeStartingDate) {
            this.state.totalChanges.expensesWithFixedBudgetBeforeStartingDate = this.calculateDifference(newSideBarTotals.expensesWithFixedBudgetBeforeStartingDate, oldSideBarTotals.expensesWithFixedBudgetBeforeStartingDate);
        }

        if (newSideBarTotals.expensesWithFixedBudgetAfterStartingDate !== oldSideBarTotals.expensesWithFixedBudgetAfterStartingDate) {
            this.state.totalChanges.expensesWithFixedBudgetAfterStartingDate = this.calculateDifference(newSideBarTotals.expensesWithFixedBudgetAfterStartingDate, oldSideBarTotals.expensesWithFixedBudgetAfterStartingDate);
        }

        if (newSideBarTotals.expensesWithFlexBudgetBeforeStartingDate !== oldSideBarTotals.expensesWithFlexBudgetBeforeStartingDate) {
            this.state.totalChanges.expensesWithFlexBudgetBeforeStartingDate = this.calculateDifference(newSideBarTotals.expensesWithFlexBudgetBeforeStartingDate, oldSideBarTotals.expensesWithFlexBudgetBeforeStartingDate);
        }

        if (newSideBarTotals.expensesWithFlexBudgetAfterStartingDate !== oldSideBarTotals.expensesWithFlexBudgetAfterStartingDate) {
            this.state.totalChanges.expensesWithFlexBudgetAfterStartingDate = this.calculateDifference(newSideBarTotals.expensesWithFlexBudgetAfterStartingDate, oldSideBarTotals.expensesWithFlexBudgetAfterStartingDate);
        }

        if (newSideBarTotals.remainingBalance !== oldSideBarTotals.remainingBalance) {
            this.state.totalChanges.remainingBalance = this.calculateDifference(newSideBarTotals.remainingBalance, oldSideBarTotals.remainingBalance);
        }
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
     * @param that
     */
    respondToMouseEnterOnTotalsButton: function (that) {
        that.hoveringTotalsButton = true;
        setTimeout(function () {
            if (that.hoveringTotalsButton) {
                that.show.basicTotals = true;
                that.show.budgetTotals = true;
            }
        }, 500);
    },

    /**
     * 
     * @param that
     */
    respondToMouseLeaveOnTotalsButton: function (that) {
        that.hoveringTotalsButton = false;
        setTimeout(function () {
            if (!that.hoveringTotalsButton) {
                that.show.basicTotals = false;
                that.show.budgetTotals = false;
            }
        }, 500);
    }
};