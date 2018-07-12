export default {


    /**
     *
     * @returns {{remainingBalance: number, remainingFixedBudget: number, cumulativeFixedBudget: number, credit: number, debit: number, balance: number, reconciledSum: number, expensesWithoutBudget: number, savings: number, expensesWithFixedBudgetBeforeStartingDate: number, expensesWithFixedBudgetAfterStartingDate: number, expensesWithFlexBudgetBeforeStartingDate: number, expensesWithFlexBudgetAfterStartingDate: number}}
     */
    resetTotalChanges: function () {
        $.each(store.state.totalChanges, function (key, value) {
            store.set(0, 'totalChanges.' + key);
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
        var newSideBarTotals = store.state.sideBarTotals;

        var that = this;
        $.each(newSideBarTotals, function (key, value) {
            if (newSideBarTotals[key] !== oldSideBarTotals[key]) {
                var diff = that.calculateDifference(newSideBarTotals[key], oldSideBarTotals[key]);
                store.set(diff, 'totalChanges.' + key);
            }
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

//     /**
//      *
//      * @param that
//      */
//     respondToMouseEnterOnTotalsButton: function (that) {
//         that.hoveringTotalsButton = true;
//         setTimeout(function () {
//             if (that.hoveringTotalsButton) {
//                 that.show.basicTotals = true;
//                 that.show.budgetTotals = true;
//             }
//         }, 500);
//     },
//
//     /**
//      *
//      * @param that
//      */
//     respondToMouseLeaveOnTotalsButton: function (that) {
//         that.hoveringTotalsButton = false;
//         setTimeout(function () {
//             if (!that.hoveringTotalsButton) {
//                 that.show.basicTotals = false;
//                 that.show.budgetTotals = false;
//             }
//         }, 500);
//     }
};