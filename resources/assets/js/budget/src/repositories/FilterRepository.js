import TransactionsRepository from './TransactionsRepository'
import FilterRepository from './FilterRepository'
import helpers from './Helpers.js'
import Vue from 'vue'
export default {

    state: {

    },

    /**
     *
     * @returns {FilterRepository.state.filter|{}}
     */
    resetFilter: function () {
        var filter = {

            total: {
                in: "",
                out: ""
            },
            types: {
                in: [],
                out: []
            },
            accounts: {
                in: [],
                out: []
            },
            singleDate: {
                in: '',
                out: ''
            },
            fromDate: {
                in: '',
                out: ''
            },
            toDate: {
                in: '',
                out: ''
            },
            description: {
                in: "",
                out: ""
            },
            merchant: {
                in: "",
                out: ""
            },
            budgets: {
                in: {
                    and: [],
                    or: []
                },
                out: []
            },
            numBudgets: {
                in: "all",
                out: ""
            },
            reconciled: "any",
            invalidAllocation: false,
            offset: 0,
            numToFetch: 30,
            displayFrom: 1,
            displayTo: 30
        };
        store.set(filter, 'filter');
    },

    /**
     * For setting the filter when a saved filter is chosen
     * @param savedFilter
     * @returns {*}
     */
    setFields: function (savedFilter) {
        store.set(savedFilter, 'filter');

        // this.state.filter.types = savedFilter.types;
        // this.state.filter.accounts = savedFilter.accounts;
        // this.state.filter.singleDate = savedFilter.singleDate;
        // this.state.filter.fromDate = savedFilter.fromDate;
        // this.state.filter.toDate = savedFilter.toDate;
        // this.state.filter.description = savedFilter.description;
        // this.state.filter.merchant = savedFilter.merchant;
        // this.state.filter.budgets = savedFilter.budgets;
        // this.state.filter.numBudgets = savedFilter.numBudgets;
        // this.state.filter.reconciled = savedFilter.reconciled;
        // this.state.filter.invalidAllocation = savedFilter.invalidAllocation;
        // this.state.filter.offset = savedFilter.offset;
        // this.state.filter.numToFetch = savedFilter.numToFetch;
        // this.state.filter.displayFrom = savedFilter.displayFrom;
        // this.state.filter.displayTo = savedFilter.displayTo;
    },

    /**
     * Used for switching to transactions page from accounts page,
     * for viewing the transactions for the chosen account
     * @param filter
     */
    setFilter: function (filter) {
        store.set(filter, 'filter');
    },

    /**
     *
     * @returns {FilterRepository.state.filter|{}}
     */
    formatDates: function () {
        var filter = store.state.filter;
        if (filter.singleDate.in) {
            filter.singleDate.inSql = helpers.convertToMySqlDate(filter.singleDate.in);
        }
        else {
            filter.singleDate.inSql = "";
        }
        if (filter.singleDate.out) {
            filter.singleDate.outSql = helpers.convertToMySqlDate(filter.singleDate.out);
        }
        else {
            filter.singleDate.outSql = "";
        }
        if (filter.fromDate.in) {
            filter.fromDate.inSql = helpers.convertToMySqlDate(filter.fromDate.in);
        }
        else {
            filter.fromDate.inSql = "";
        }
        if (filter.fromDate.out) {
            filter.fromDate.outSql = helpers.convertToMySqlDate(filter.fromDate.out);
        }
        else {
            filter.fromDate.outSql = "";
        }
        if (filter.toDate.in) {
            filter.toDate.inSql = helpers.convertToMySqlDate(filter.toDate.in);
        }
        else {
            filter.toDate.inSql = "";
        }
        if (filter.toDate.out) {
            filter.toDate.outSql = helpers.convertToMySqlDate(filter.toDate.out);
        }
        else {
            filter.toDate.outSql = "";
        }

        return filter;
    },

    /**
     * Updates filter.displayFrom and filter.displayTo values
     */
    updateRange: function (numToFetch) {
        if (numToFetch) {
            store.set(numToFetch, 'filter.numToFetch');
        }
        store.set(store.state.filter.offset+1, 'filter.displayFrom');
        store.set(store.state.filter.offset+(store.state.filter.numToFetch * 1), 'filter.displayTo');
    },

    /**
     *
     * @param that
     */
    prevResults: function () {
        //make it so the offset cannot be less than 0.
        if (store.state.filter.offset - store.state.filter.numToFetch < 0) {
            store.set(0, 'filter.offset');
        }
        else {
            store.set(store.state.filter.offset - (store.state.filter.numToFetch * 1), 'filter.offset');
            this.updateRange(store.state.filter.numToFetch);
            this.runFilter();
        }
    },

    /**
     *
     * @param that
     */
    nextResults: function () {
        if (store.state.filter.offset + (store.state.filter.numToFetch * 1) > store.filterTotals.numTransactions) {
            //stop it going past the end.
            return;
        }

        store.set(store.state.filter.offset+(store.state.filter.numToFetch * 1), 'filter.offset');
        this.updateRange(store.state.filter.numToFetch);
        this.runFilter();
    },

    /**
     * type1 is 'in' or 'out'.
     * type2 is 'and' or 'or'.
     * @param type1
     * @param type2
     */
    clearBudgetField: function (type1, type2) {
        //Todo: don't edit store state like this
        if (type2) {
            store.state.filter.budgets[type1][type2] = [];
        }
        else {
            store.state.filter.budgets[type1] = [];
        }
        this.runFilter();
    },

    /**
     *
     * @param field
     * @param type
     */
    clearFilterField: function (field, type) {
        //Todo: don't edit store state like this
        this.state.filter[field][type] = "";
        this.runFilter();
    },

    /**
     *
     */
    resetOffset: function () {
        store.set(0, 'filter.offset');
    },

    /**
     * Todo: The ToolbarForFilterComponent also needs the totals
     * Todo: should be GET not POST
     */
    getBasicFilterTotals: function () {
        var filter = this.formatDates();

        var data = {
            filter: filter
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
    runFilter: function () {
        // console.log('running filter...route path is: ' + helpers.getRoutePath());
        this.getBasicFilterTotals();

        if (helpers.getRoutePath()) {
            TransactionsRepository.filterTransactions();
        }
        else {
            store.getAllGraphData();
        }
    }

}