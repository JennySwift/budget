//Redo after refactor

export default {

//
//     /**
//      *
//      * @returns {{remainingBalance: number, remainingFixedBudget: number, cumulativeFixedBudget: number, credit: number, debit: number, balance: number, reconciledSum: number, expensesWithoutBudget: number, savings: number, expensesWithFixedBudgetBeforeStartingDate: number, expensesWithFixedBudgetAfterStartingDate: number, expensesWithFlexBudgetBeforeStartingDate: number, expensesWithFlexBudgetAfterStartingDate: number}}
//      */
//     resetTotalChanges: function () {
//         var that = this;
//         $.each(store.state.totalChanges, function (key, value) {
//             that.state.totalChanges[key] = 0;
//         });
//     },
//
//     /**
//      *
//      * @param oldSideBarTotals
//      */
//     setTotalChanges: function (oldSideBarTotals) {
//         if (oldSideBarTotals.remainingBalance === '') {
//             //Totals were just loaded for the first time, not changing
//             return false;
//         }
//         var newSideBarTotals = store.state.sideBarTotals;
//
//         if (newSideBarTotals.credit !== oldSideBarTotals.credit) {
//             store.state.totalChanges.credit = this.calculateDifference(newSideBarTotals.credit, oldSideBarTotals.credit);
//         }
//
//         if (newSideBarTotals.debit !== oldSideBarTotals.debit) {
//             store.state.totalChanges.debit = this.calculateDifference(newSideBarTotals.debit, oldSideBarTotals.debit);
//         }
//
//         if (newSideBarTotals.balance !== oldSideBarTotals.balance) {
//             store.state.totalChanges.balance = this.calculateDifference(newSideBarTotals.balance, oldSideBarTotals.balance);
//         }
//
//         if (newSideBarTotals.reconciledSum !== oldSideBarTotals.reconciledSum) {
//             store.state.totalChanges.reconciledSum = this.calculateDifference(newSideBarTotals.reconciledSum, oldSideBarTotals.reconciledSum);
//         }
//
//         if (newSideBarTotals.savings !== oldSideBarTotals.savings) {
//             store.state.totalChanges.savings = this.calculateDifference(newSideBarTotals.savings, oldSideBarTotals.savings);
//         }
//
//         if (newSideBarTotals.expensesWithoutBudget !== oldSideBarTotals.expensesWithoutBudget) {
//             store.state.totalChanges.expensesWithoutBudget = this.calculateDifference(newSideBarTotals.expensesWithoutBudget, oldSideBarTotals.expensesWithoutBudget);
//         }
//
//         if (newSideBarTotals.remainingFixedBudget !== oldSideBarTotals.remainingFixedBudget) {
//             store.state.totalChanges.remainingFixedBudget = this.calculateDifference(newSideBarTotals.remainingFixedBudget, oldSideBarTotals.remainingFixedBudget);
//         }
//
//         if (newSideBarTotals.cumulativeFixedBudget !== oldSideBarTotals.cumulativeFixedBudget) {
//             store.state.totalChanges.cumulativeFixedBudget = this.calculateDifference(newSideBarTotals.cumulativeFixedBudget, oldSideBarTotals.cumulativeFixedBudget);
//         }
//
//         if (newSideBarTotals.expensesWithFixedBudgetBeforeStartingDate !== oldSideBarTotals.expensesWithFixedBudgetBeforeStartingDate) {
//             store.state.totalChanges.expensesWithFixedBudgetBeforeStartingDate = this.calculateDifference(newSideBarTotals.expensesWithFixedBudgetBeforeStartingDate, oldSideBarTotals.expensesWithFixedBudgetBeforeStartingDate);
//         }
//
//         if (newSideBarTotals.expensesWithFixedBudgetAfterStartingDate !== oldSideBarTotals.expensesWithFixedBudgetAfterStartingDate) {
//             store.state.totalChanges.expensesWithFixedBudgetAfterStartingDate = this.calculateDifference(newSideBarTotals.expensesWithFixedBudgetAfterStartingDate, oldSideBarTotals.expensesWithFixedBudgetAfterStartingDate);
//         }
//
//         if (newSideBarTotals.expensesWithFlexBudgetBeforeStartingDate !== oldSideBarTotals.expensesWithFlexBudgetBeforeStartingDate) {
//             store.state.totalChanges.expensesWithFlexBudgetBeforeStartingDate = this.calculateDifference(newSideBarTotals.expensesWithFlexBudgetBeforeStartingDate, oldSideBarTotals.expensesWithFlexBudgetBeforeStartingDate);
//         }
//
//         if (newSideBarTotals.expensesWithFlexBudgetAfterStartingDate !== oldSideBarTotals.expensesWithFlexBudgetAfterStartingDate) {
//             store.state.totalChanges.expensesWithFlexBudgetAfterStartingDate = this.calculateDifference(newSideBarTotals.expensesWithFlexBudgetAfterStartingDate, oldSideBarTotals.expensesWithFlexBudgetAfterStartingDate);
//         }
//
//         if (newSideBarTotals.remainingBalance !== oldSideBarTotals.remainingBalance) {
//             store.state.totalChanges.remainingBalance = this.calculateDifference(newSideBarTotals.remainingBalance, oldSideBarTotals.remainingBalance);
//         }
//     },
//
//     /**
//      * @param newValue
//      * @param oldValue
//      * @returns {string}
//      */
//     calculateDifference: function (newValue, oldValue) {
//         var diff = newValue - oldValue;
//         return diff.toFixed(2);
//     },
//
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