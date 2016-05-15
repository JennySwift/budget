var BudgetsRepository = {

    /**
     * 
     * @param budgets
     * @param that
     * @returns {*}
     */
    orderBudgetsFilter: function (budgets, that) {
        switch(that.orderBy) {
            case 'name':
                budgets = _.sortBy(budgets, 'name');
                break;
            case 'spentAfterStartingDate':
                budgets = _.sortBy(budgets, 'spentAfterStartingDate');
                break;
        }

        if (that.reverseOrder) {
            budgets = budgets.reverse();
        }

        return budgets;
    }
};