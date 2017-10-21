import helpers from './Helpers'
var object = require('lodash/object');
require('sugar');
Date.setLocale('en-AU');
import Vue from 'vue'
import GraphsRepository from './GraphsRepository'
// import NewTransactionRepository from './NewTransactionRepository'


export default {
    state: {
        me: {
            gravatar: '',
            preferences: {
                show: {
                    totals: ''
                },
                colors: {}
            },
        },
        env: '',
        //For home page tabs
        tab: '',
        loading: false,
        accounts: [],
        accountsWithBalances: [],
        budgets: [],
        transaction: {
            account: {},
            budgets: [
                {name: ''}
            ]
        },
        selectedTransaction: {},
        selectedBudget: {},
        selectedAccount: {},
        fixedBudgetTotals: {},
        flexBudgetTotals: {},
        transactions: [
            {
                account: {},
                budgets: [
                    {name: ''}
                ]
            }
        ],
        fixedBudgets: [],
        flexBudgets: [],
        unassignedBudgets: [],
        favouriteTransactions: [],
        newFavouriteTransaction: {
            account: {},
            fromAccount: {},
            toAccount: {},
            budgets: [],
            type: 'expense'
        },
        selectedFavouriteTransaction: {},
        newTransaction: {
            userDate: 'today',
            type: 'expense',
            account: {},
            fromAccount: {},
            toAccount: {},
            duration: '',
            total: '',
            merchant: '',
            description: '',
            reconciled: false,
            multipleBudgets: false,
            budgets: []
        },
        // newTransactionDefaults: {
        //     userDate: 'today',
        //     type: 'expense',
        //     account: {},
        //     fromAccount: {},
        //     toAccount: {},
        //     duration: '',
        //     total: '',
        //     merchant: '',
        //     description: '',
        //     reconciled: false,
        //     multipleBudgets: false,
        //     budgets: []
        // },
        filters: {

        },
        savedFilters: [],
        //For editing fields in item popup before the item is saved
        selectedItemClone: {

        },
        selectedItem: {

        },
        newItem: {},
        showPopup: false,
        showFilter: true,
        show: {
            newTransaction: false,
            totals: false,
            // basicTotals: false,
            // budgetTotals: false,
            filterTotals: true,
            budget: false,
            filter: true,
            savingsTotal: {
                input: false,
                edit_btn: true
            },
            newBudget: false
        },
        transactionPropertiesToShow: {
            status: false,
            date: true,
            description: true,
            merchant: true,
            total: true,
            type: true,
            account: true,
            duration: true,
            reconciled: true,
            allocated: true,
            budgets: true,
            delete: true,
        }
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
        helpers.get({
            url: '/api/environment',
            storeProperty: 'env',
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
    getAccountsWithBalances: function () {
        helpers.get({
            url: '/api/accounts?includeBalance=true',
            storeProperty: 'accountsWithBalances',
            callback: function () {
                // store.setNewTransactionDefaults();
                // store.setNewFavouriteTransactionAccount();
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
                store.setNewTransactionDefaults();
                store.setNewFavouriteTransactionAccount();
            }
        });
    },

    setNewFavouriteTransactionAccount: function () {
        store.set(this.state.accounts[0], 'newFavouriteTransaction.account');
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
        if (this.state.env === 'local') {
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
        switch(that.orderBy) {
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


    /**
     * Add an item to an array
     * @param item
     * @param path
     */
    add: function (item, path) {
        object.get(this.state, path).push(item);
    },

    /**
     * Update an item that is in an array that is in the store
     * @param item
     * @param path
     */
    update: function (item, path) {
        var index = helpers.findIndexById(object.get(this.state, path), item.id);

        Vue.set(object.get(this.state, path), index, item);
    },

    /**
     * Set a property that is in the store (can be nested)
     * @param data
     * @param path
     */
    set: function (data, path) {
        object.set(this.state, path, data);
    },

    /**
     * Toggle a property that is in the store (can be nested)
     * @param path
     */
    toggle: function (path) {
        object.set(this.state, path, !object.get(this.state, path));
    },

    /**
     * Delete an item from an array in the store
     * To delete a nested property of store.state, for example a class in store.state.classes.data:
     * store.delete(itemToDelete, 'student.classes.data');
     * @param itemToDelete
     * @param path
     */
    delete: function (itemToDelete, path) {
        // console.log('\n\n get: ' + JSON.stringify(object.get(this.state, path), null, 4) + '\n\n');
        // console.log('\n\n item to delete: ' + JSON.stringify(itemToDelete, null, 4) + '\n\n');
        object.set(this.state, path, helpers.deleteById(object.get(this.state, path), itemToDelete.id));
    }
}