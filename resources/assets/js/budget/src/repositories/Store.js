import helpers from './Helpers'
var object = require('lodash/object');
require('sugar');
Date.setLocale('en-AU');
import Vue from 'vue'


export default {
    state: {
        me: {
            gravatar: '',
            preferences: {
                show: {
                    totals: ''
                }
            }
        },
        //For home page tabs
        tab: '',
        loading: false,
        accounts: [],
        budgets: [],
        fixedBudgets: [],
        flexBudgets: [],
        unassignedBudgets: [],
        favouriteTransactions: [],
        newTransaction: {
            defaults: {
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
            }
        },
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
        transactionPropertiesToShow: {}
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
        if (env && env === 'local') {
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
     * @returns {*}
     */
    getNewTransactionDefaults: function () {
        //Fill in the new transaction fields if development environment
        //Todo: get env
        if (env === 'local') {
            this.state.defaults.total = 10;
            this.state.defaults.merchant = 'some merchant';
            this.state.defaults.description = 'some description';
            this.state.defaults.budgets = [
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
            this.state.defaults.account = accounts[0];
            this.state.defaults.fromAccount = accounts[0];
            this.state.defaults.toAccount = accounts[0];
        }

        return this.state.defaults;
    },

    /**
     *
     * @param env
     * @param me
     * @param newTransaction
     * @returns {*}
     */
    clearNewTransactionFields: function (newTransaction) {
        //Todo: need to fetch 'me'
        if (me.preferences.clearFields) {
            newTransaction.budgets = [];
            newTransaction.total = '';
            newTransaction.description = '';
            newTransaction.merchant = '';
            newTransaction.reconciled = false;
            newTransaction.multipleBudgets = false;
        }

        return newTransaction;
    },


    /**
     *
     * @param newTransaction
     * @returns {*}
     */
    anyNewTransactionErrors: function (newTransaction) {
        var messages = [];

        //if (!Date.parse(newTransaction.userDate)) {
        //    messages.push('Date is not valid');
        //}
        //else {
        //    newTransaction.date = Date.parse(newTransaction.userDate).toString('yyyy-MM-dd');
        //}

        if (newTransaction.total === "") {
            messages.push('Total is required');
        }
        else if (!$.isNumeric(newTransaction.total)) {
            messages.push('Total is not a valid number');
        }

        if (messages.length > 0) {
            return messages;
        }

        return false;
    },

    /**
     *
     */
    getAccounts: function () {
        helpers.get({
            url: '/api/accounts?includeBalance=true',
            storeProperty: 'accounts'
        });
    },

    /**
     *
     */
    getSavedFilters: function () {
        helpers.get({
            url: '/api/savedFilters',
            storeProperty: 'accounts'
        });
    },

    /**
     *
     */
    favouriteTransactions: function () {
        helpers.get({
            url: '/api/favouriteTransactions',
            storeProperty: 'favouriteTransactions'
        });
    },

    /**
     *
     * @param favouriteTransaction
     * @returns {{name: *, type: *, description: *, merchant: *, total: *, budget_ids}}
     */
    setFavouriteTransactionFields: function (favouriteTransaction) {
        var data = {
            name: favouriteTransaction.name,
            type: favouriteTransaction.type,
            description: favouriteTransaction.description,
            merchant: favouriteTransaction.merchant,
            total: favouriteTransaction.total,
            budget_ids: _.pluck(favouriteTransaction.budgets, 'id')
        };

        if (favouriteTransaction.account && favouriteTransaction.type !== 'transfer') {
            data.account_id = favouriteTransaction.account.id;
        }

        if (favouriteTransaction.fromAccount && favouriteTransaction.type === 'transfer') {
            data.from_account_id = favouriteTransaction.fromAccount.id;
        }

        if (favouriteTransaction.toAccount && favouriteTransaction.type === 'transfer') {
            data.to_account_id = favouriteTransaction.toAccount.id;
        }

        return data;
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
     * @param budget
     */
    updateBudget: function (budget, that) {
        //Update the budgets array
        var index = HelpersRepository.findIndexById(this.state.budgets, budget.id);
        this.state.budgets.$set(index, budget);

        if (that.page !== budget.type) {
            //The budget type has changed. Remove it from the specific budgets array it was in, and add it to another specific budgets array.
            this.deleteBudgetFromSpecificArray(budget, that);
            this.addBudgetToSpecificArray(budget, that);
        }
        else {
            //The budget type has not changed. Update the specific budgets array
            switch(that.page) {
                case 'fixed':
                    index = HelpersRepository.findIndexById(this.state.fixedBudgets, budget.id);
                    this.state.fixedBudgets.$set(index, budget);
                    break;
                case 'flex':
                    index = HelpersRepository.findIndexById(this.state.flexBudgets, budget.id);
                    this.state.flexBudgets.$set(index, budget);
                    break;
                case 'unassigned':
                    index = HelpersRepository.findIndexById(this.state.unassignedBudgets, budget.id);
                    this.state.unassignedBudgets.$set(index, budget);
                    break;
            }
        }
    },

    /**
     * Remove budget from budgets array as well as from specific budgets array
     * @param budget
     * @param that
     */
    deleteBudget: function (budget, that) {
        //Remove from budgets array
        this.state.budgets = HelpersRepository.deleteById(this.state.budgets, budget.id);

        this.deleteBudgetFromSpecificArray(budget, that);
    },

    /**
     *
     * @param budget
     * @param that
     */
    deleteBudgetFromSpecificArray: function(budget, that) {
        switch(that.page) {
            case 'fixed':
                this.state.fixedBudgets = HelpersRepository.deleteById(this.state.fixedBudgets, budget.id);
                break;
            case 'flex':
                this.state.flexBudgets = HelpersRepository.deleteById(this.state.flexBudgets, budget.id);
                break;
            case 'unassigned':
                this.state.unassignedBudgets = HelpersRepository.deleteById(this.state.unassignedBudgets, budget.id);
                break;
        }
    },

    /**
     *
     * @param budget
     * @param that
     */
    addBudgetToSpecificArray: function(budget, that) {
        switch(budget.type) {
            case 'fixed':
                this.state.fixedBudgets.push(budget);
                break;
            case 'flex':
                this.state.flexBudgets.push(budget);
                break;
            case 'unassigned':
                this.state.unassignedBudgets.push(budget);
                break;
        }
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
    showLoading: function () {
        this.state.loading = true;
    },

    /**
     *
     */
    hideLoading: function () {
        this.state.loading = false;
    },

    getItems: function () {
        helpers.get({
            url: ItemsRepository.getUrl(),
            storeProperty: 'items',
            loadedProperty: 'itemsLoaded',
            callback: function (response) {
                this.zoom(response);
            }.bind(this)
        });
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