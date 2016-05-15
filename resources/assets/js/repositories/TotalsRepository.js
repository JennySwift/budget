var TotalsRepository = {

    /**
     *
     * @returns {{remainingBalance: number, remainingFixedBudget: number, cumulativeFixedBudget: number, credit: number, debit: number, balance: number, reconciledSum: number, expensesWithoutBudget: number, savings: number, expensesWithFixedBudgetBeforeStartingDate: number, expensesWithFixedBudgetAfterStartingDate: number, expensesWithFlexBudgetBeforeStartingDate: number, expensesWithFlexBudgetAfterStartingDate: number}}
     */
    resetTotalChanges: function () {
        return {
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
        };
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