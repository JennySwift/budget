import helpers from "./helpers/Helpers";
import TotalsRepository from "./TotalsRepository";
import FilterRepository from "./FilterRepository";
import NewTransactionRepository from "./NewTransactionRepository";

export default {
    /**
     *
     */
    getSideBarTotals: function () {
        this.state.totalsLoading = true;
        var oldSideBarTotals = this.state.sideBarTotals;

        helpers.get({
            url: '/api/totals/sidebar',
            storeProperty: 'sideBarTotals',
            // loadedProperty: 'itemsLoaded',
            callback: function (response) {
                TotalsRepository.setTotalChanges(oldSideBarTotals);
                store.state.totalsLoading = false;
            }.bind(this)
        });
    },

    /**
     * Todo: The ToolbarForFilterComponent also needs the totals
     * Todo: should be GET not POST
     */
    getBasicFilterTotals: function () {
        var data = {
            filter: FilterRepository.formatDates()
        };

        helpers.post({
            url: '/api/filter/basicTotals',
            data: data,
            callback: function (response) {
                store.set(response, 'filterTotals');
            }.bind(this)
        });
    },

    /**
     *
     */
    getUser: function () {
        helpers.get({
            url: '/api/users/current',
            storeProperty: 'me',
        });
    },

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
     */
    filterTransactions: function () {
        var data = {
            filter: FilterRepository.formatDates()
        };

        helpers.get({
            url: '/api/filter/transactions',
            data: data,
            storeProperty: 'transactions'
        });
    },

    /**
     *
     */
    getFavouriteTransactions: function () {
        helpers.get({
            url: '/api/favouriteTransactions',
            storeProperty: 'favouriteTransactions'
        });
    },

    /**
     *
     */
    getAllocationTotals: function () {
        helpers.get({
            url: '/api/transactions/' + this.state.selectedTransactionForAllocation.id,
            storeProperty: 'allocationTotals'
        });
    },

    /**
     *
     */
    getBudgets: function () {
        helpers.get({
            url: '/api/budgets',
            storeProperty: 'budgets'
        });
    },

    /**
     *
     */
    getFixedBudgets: function () {
        helpers.get({
            url: '/api/budgets?fixed=true',
            storeProperty: 'fixedBudgets'
        });
    },

    /**
     *
     */
    getFlexBudgets: function () {
        helpers.get({
            url: '/api/budgets?flex=true',
            storeProperty: 'flexBudgets'
        });
    },

    /**
     *
     */
    getUnassignedBudgets: function () {
        helpers.get({
            url: '/api/budgets?unassigned=true',
            storeProperty: 'unassignedBudgets'
        });
    },

    /**
     *
     */
    getFixedBudgetTotals: function () {
        helpers.get({
            url: '/api/totals/fixedBudget',
            storeProperty: 'fixedBudgetTotals',
        });
    },

    /**
     *
     */
    getFlexBudgetTotals: function () {
        helpers.get({
            url: '/api/totals/flexBudget',
            storeProperty: 'flexBudgetTotals',
        });
    },

    /**
     *
     */
    getAccountsWithBalances: function () {
        helpers.get({
            url: '/api/accounts?includeBalance=true',
            storeProperty: 'accountsWithBalances',
            callback: function () {
                // store.setNewTransactionDefaults();
                // NewTransactionRepository.setNewFavouriteTransactionAccount();
            }
        });
    },

    /**
     * Do not get the account balances
     */
    getAccounts: function () {
        helpers.get({
            url: '/api/accounts',
            storeProperty: 'accounts',
            callback: function () {
                NewTransactionRepository.setNewTransactionDefaults();
                NewTransactionRepository.setNewFavouriteTransactionAccount();
            }
        });
    },

    /**
     *
     */
    getSavedFilters: function () {
        helpers.get({
            url: '/api/savedFilters',
            storeProperty: 'savedFilters'
        });
    },
}