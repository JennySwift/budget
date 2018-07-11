import helpers from './helpers/Helpers'
import Vue from 'vue'
import GraphsRepository from './GraphsRepository'
import FilterRepository from "./FilterRepository";
import TotalsRepository from "./TotalsRepository";
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

    //Helpers
    add: storeHelpers.add,
    update: storeHelpers.update,
    set: storeHelpers.set,
    toggle: storeHelpers.toggle,
    delete: storeHelpers.delete,
    without: storeHelpers.without,


    /**
     *
     */
    getEnvironment: function () {
        // helpers.get({
        //     url: '/api/environment',
        //     storeProperty: 'env',
        // });
    },
    
    /**
     *
     * @param transaction
     */
    showAllocationPopup: function (transaction) {
        store.set(transaction, 'selectedTransactionForAllocation');
        store.getAllocationTotals();
        helpers.showPopup('allocation');
    },

    setNewFavouriteTransactionAccount: function () {
        store.set(this.state.accounts[0], 'newFavouriteTransaction.account');
    },

    /**
     *
     * @returns {{status: boolean, date: boolean, description: boolean, merchant: boolean, total: boolean, type: boolean, account: boolean, duration: boolean, reconciled: boolean, allocated: boolean, budgets: boolean}}
     */
    setDefaultTransactionPropertiesToShow: function () {
        this.transactionPropertiesToShow = {
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
    },

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
     */
    setNewTransactionDefaults: function () {
        if (helpers.isLocalEnvironment()) {
            this.state.newTransaction.total = 10;
            this.state.newTransaction.merchant = 'some merchant';
            this.state.newTransaction.description = 'some description';
            this.state.newTransaction.budgets = [
                {
                    id: '2',
                    name: 'business',
                    type: 'fixed'
                },
                //{
                //    id: '4',
                //    name: 'busking',
                //    type: 'flex'
                //}
            ];
        }

        if (this.state.accounts && this.state.accounts.length > 0) {
            this.state.newTransaction.account = this.state.accounts[0];
            this.state.newTransaction.fromAccount = this.state.accounts[0];
            this.state.newTransaction.toAccount = this.state.accounts[0];
        }
    },


    /**
     *
     * @param favouriteTransaction
     * @returns {{name: *, type: *, description: *, merchant: *, total: *, budget_ids}}
     */
    setFavouriteTransactionFields: function () {
        var data = {
            name: this.state.newFavouriteTransaction.name,
            type: this.state.newFavouriteTransaction.type,
            description: this.state.newFavouriteTransaction.description,
            merchant: this.state.newFavouriteTransaction.merchant,
            total: this.state.newFavouriteTransaction.total,
            budget_ids: _.map(this.state.newFavouriteTransaction.budgets, 'id')
        };

        if (this.state.newFavouriteTransaction.account && this.state.newFavouriteTransaction.type !== 'transfer') {
            data.account_id = this.state.newFavouriteTransaction.account.id;
        }

        if (this.state.newFavouriteTransaction.fromAccount && this.state.newFavouriteTransaction.type === 'transfer') {
            data.from_account_id = this.state.newFavouriteTransaction.fromAccount.id;
        }

        if (this.state.newFavouriteTransaction.toAccount && this.state.newFavouriteTransaction.type === 'transfer') {
            data.to_account_id = this.state.newFavouriteTransaction.toAccount.id;
        }

        return data;
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