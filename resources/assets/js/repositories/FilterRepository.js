var FilterRepository = {

    state: {
        filter: {},
        filterTotals: {}
    },

    /**
     *
     * @returns {FilterRepository.state.filter|{}}
     */
    resetFilter: function () {
        this.state.filter = {

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

        return this.state.filter;
    },

    /**
     * For setting the filter when a saved filter is chosen
     * @param savedFilter
     * @returns {*}
     */
    setFields: function (savedFilter) {
        this.state.filter.total = savedFilter.total;
        this.state.filter.types = savedFilter.types;
        this.state.filter.accounts = savedFilter.accounts;
        this.state.filter.singleDate = savedFilter.singleDate;
        this.state.filter.fromDate = savedFilter.fromDate;
        this.state.filter.toDate = savedFilter.toDate;
        this.state.filter.description = savedFilter.description;
        this.state.filter.merchant = savedFilter.merchant;
        this.state.filter.budgets = savedFilter.budgets;
        this.state.filter.numBudgets = savedFilter.numBudgets;
        this.state.filter.reconciled = savedFilter.reconciled;
        this.state.filter.invalidAllocation = savedFilter.invalidAllocation;
        this.state.filter.offset = savedFilter.offset;
        this.state.filter.numToFetch = savedFilter.numToFetch;
        this.state.filter.displayFrom = savedFilter.displayFrom;
        this.state.filter.displayTo = savedFilter.displayTo;
    },

    /**
     *
     * @returns {FilterRepository.state.filter|{}}
     */
    formatDates: function () {
        var filter = FilterRepository.state.filter;
        if (filter.singleDate.in) {
            filter.singleDate.inSql = HelpersRepository.formatDate(filter.singleDate.in);
        }
        else {
            filter.singleDate.inSql = "";
        }
        if (filter.singleDate.out) {
            filter.singleDate.outSql = HelpersRepository.formatDate(filter.singleDate.out);
        }
        else {
            filter.singleDate.outSql = "";
        }
        if (filter.fromDate.in) {
            filter.fromDate.inSql = HelpersRepository.formatDate(filter.fromDate.in);
        }
        else {
            filter.fromDate.inSql = "";
        }
        if (filter.fromDate.out) {
            filter.fromDate.outSql = HelpersRepository.formatDate(filter.fromDate.out);
        }
        else {
            filter.fromDate.outSql = "";
        }
        if (filter.toDate.in) {
            filter.toDate.inSql = HelpersRepository.formatDate(filter.toDate.in);
        }
        else {
            filter.toDate.inSql = "";
        }
        if (filter.toDate.out) {
            filter.toDate.outSql = HelpersRepository.formatDate(filter.toDate.out);
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
            this.state.filter.numToFetch = numToFetch;
        }

        this.state.filter.displayFrom = this.state.filter.offset + 1;
        this.state.filter.displayTo = this.state.filter.offset + (this.state.filter.numToFetch * 1);
    },

    /**
     *
     * @param that
     */
    prevResults: function (that) {
        this.state.filter = that.filter;
        //make it so the offset cannot be less than 0.
        if (this.state.filter.offset - this.state.filter.numToFetch < 0) {
            this.state.filter.offset = 0;
        }
        else {
            this.state.filter.offset-= (this.state.filter.numToFetch * 1);
            this.updateRange(this.state.filter.numToFetch);
            that.runFilter();
        }
    },

    /**
     *
     * @param that
     */
    nextResults: function (that) {
        this.state.filter = that.filter;
        if (this.state.filter.offset + (this.state.filter.numToFetch * 1) > that.filterTotals.numTransactions) {
            //stop it going past the end.
            return;
        }

        this.state.filter.offset+= (this.state.filter.numToFetch * 1);
        this.updateRange(this.state.filter.numToFetch);
        that.runFilter();
    },

    /**
     * type1 is 'in' or 'out'.
     * type2 is 'and' or 'or'.
     * @param that
     * @param type1
     * @param type2
     */
    clearBudgetField: function (that, type1, type2) {
        if (type2) {
            this.state.filter.budgets[type1][type2] = [];
        }
        else {
            this.state.filter.budgets[type1] = [];
        }
        that.runFilter();
    },

    /**
     *
     * @param that
     * @param field
     * @param type
     */
    clearFilterField: function (that, field, type) {
        this.state.filter[field][type] = "";
        that.runFilter();
    },

    /**
     *
     */
    resetOffset: function () {
        this.state.filter.offset = 0;
    },

    /**
     * Todo: The ToolbarForFilterComponent also needs the totals
     * Todo: should be GET not POST
     */
    getBasicFilterTotals: function (that) {
        var filter = this.formatDates();

        var data = {
            filter: filter
        };

        $.event.trigger('show-loading');
        that.$http.post('/api/filter/basicTotals', data, function (response) {
            FilterRepository.state.filterTotals = response;
            $.event.trigger('hide-loading');
        })
        .error(function (response) {
            HelpersRepository.handleResponseError(response);
        });
    },

    /**
     *
     * @param that
     */
    runFilter: function (that) {
        this.getBasicFilterTotals(that);
        if (HomePageRepository.state.tab === 'transactions') {
            TransactionsRepository.filterTransactions(that);
        }
        else {
            $.event.trigger('get-graph-totals');
        }
    }

};

FilterRepository.resetFilter();