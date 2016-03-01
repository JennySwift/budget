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
    }
};