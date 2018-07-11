import helpers from './helpers/Helpers'
import GraphsRepository from './GraphsRepository'
import state from '../store-state'
import getRequests from './GetRequests'
import storeHelpers from './store-helpers'

require('sugar');
Date.setLocale('en-AU');

export default {
    state: state,

    //Get Requests
    getSideBarTotals: getRequests.getSideBarTotals,
    getUser: getRequests.getUser,
    filterTransactions: getRequests.filterTransactions,
    getFavouriteTransactions: getRequests.getFavouriteTransactions,
    getAllocationTotals: getRequests.getAllocationTotals,
    getBudgets: getRequests.getBudgets,
    getFixedBudgets: getRequests.getFixedBudgets,
    getFlexBudgets: getRequests.getFlexBudgets,
    getUnassignedBudgets: getRequests.getUnassignedBudgets,
    getFixedBudgetTotals: getRequests.getFixedBudgetTotals,
    getFlexBudgetTotals: getRequests.getFlexBudgetTotals,
    getAccountsWithBalances: getRequests.getAccountsWithBalances,
    getAccounts: getRequests.getAccounts,
    getSavedFilters: getRequests.getSavedFilters,
    getEnvironment: getRequests.getEnvironment,

    //Helpers
    add: storeHelpers.add,
    update: storeHelpers.update,
    set: storeHelpers.set,
    toggle: storeHelpers.toggle,
    delete: storeHelpers.delete,
    without: storeHelpers.without,

    /**
     *
     * @returns {string}
     */
    setDefaultTab: function () {
        if (this.state.env && this.state.env === 'local') {
            this.state.tab = 'transactions';
        }
        else {
            this.state.tab = 'transactions';
        }
    },

    /**
     *
     * @param tab
     */
    setTab: function (tab) {
        this.state.tab = tab;
    },

    /**
     *
     */
    setHeights: function () {
        var height = $(window).height();
        //Uncomment after refactor
        $('body,html').height(height);
    },

    /**
     *
     * @param budgets
     * @param that
     * @returns {*}
     */
    orderBudgetsFilter: function (budgets, that) {
        switch (that.orderBy) {
            case 'name':
                budgets = _.sortBy(budgets, 'name');
                break;
            case 'spentOnOrAfterStartingDate':
                budgets = _.sortBy(budgets, 'spentOnOrAfterStartingDate');
                break;
        }

        if (that.reverseOrder) {
            budgets = budgets.reverse();
        }

        return budgets;
    },

    /**
     *
     */
    getAllGraphData: function () {
        GraphsRepository.getDoughnutChartData();
        GraphsRepository.getLineAndBarChartData();
    },


    /**
     *
     */
    showLoading: function () {
        this.state.loading = true;
    },

    /**
     *
     */
    hideLoading: function () {
        this.state.loading = false;
    },
}