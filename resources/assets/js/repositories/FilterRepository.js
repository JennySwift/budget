import TransactionsRepository from './TransactionsRepository'
import FilterRepository from './FilterRepository'
import helpers from './helpers/Helpers.js'
import filterDefaults from "../filterDefaults";
import Vue from 'vue'
export default {
    /**
     * For setting the filter when a saved filter is chosen
     * @param savedFilter
     * @returns {*}
     */
    setFields: function (savedFilter) {
        store.set(savedFilter, 'filter');
    },

    resetFilter: function () {
        store.set(helpers.clone(filterDefaults), 'filter');
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
        if (store.state.filter.offset + (store.state.filter.numToFetch * 1) > store.state.filterTotals.numTransactions) {
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
        var path = 'filter.' + field;
        if (type) {
            path += '.' + type;
        }
        store.set("", path);
        this.runFilter();
    },

    /**
     *
     */
    resetOffset: function () {
        store.set(0, 'filter.offset');
    },

    /**
     *
     */
    runFilter: function () {
        // console.log('running filter...route path is: ' + helpers.getRoutePath());
        store.getBasicFilterTotals();

        if (helpers.getRoutePath()) {
            store.filterTransactions();
        }
        else {
            store.getAllGraphData();
        }
    }

}