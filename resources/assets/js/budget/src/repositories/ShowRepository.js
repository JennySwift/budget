export default {

    defaults: {
        basicTotals: false,
        budgetTotals: false,
        filterTotals: true,
        budget: false,
        filter: false,
        savingsTotal: {
            input: false,
            edit_btn: true
        }
    },

    /**
     *
     * @returns {{status: boolean, date: boolean, description: boolean, merchant: boolean, total: boolean, type: boolean, account: boolean, duration: boolean, reconciled: boolean, allocated: boolean, budgets: boolean}}
     */
    setTransactionDefaults: function () {
        return {
            all: true,
            status: true,
            date: true,
            description: true,
            merchant: true,
            total: true,
            type: true,
            account: true,
            duration: true,
            reconciled: true,
            allocated: true,
            budgets: true
        }
    }

}