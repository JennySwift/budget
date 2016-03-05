var FilterRepository = {

    resetFilter: function () {
        this.filter = {

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
            offset: 0,
            numToFetch: 30,
            displayFrom: 1,
            displayTo: 30
        };

        $.event.trigger('reset-filter');

        return this.filter;
    },

    chooseSavedFilter: function ($savedFilter) {
        this.filter = $savedFilter;
        $rootScope.$emit('setFilterInToolbarDirective');
    },

    /**
     * Updates filter.displayFrom and filter.displayTo values
     */
    updateRange: function ($numToFetch) {
        if ($numToFetch) {
            this.filter.numToFetch = $numToFetch;
        }

        this.filter.displayFrom = this.filter.offset + 1;
        this.filter.displayTo = this.filter.offset + (this.filter.numToFetch * 1);
    },

    //Todo: I might not need some of this code (not allowing offset to be less than 0)
    // todo: since I disabled the button if that is the case
    prevResults: function () {
        //make it so the offset cannot be less than 0.
        if (this.filter.offset - this.filter.numToFetch < 0) {
            this.filter.offset = 0;
        }
        else {
            this.filter.offset-= (this.filter.numToFetch * 1);
            this.updateRange();
            $rootScope.$emit('runFilter');
        }
    },

    nextResults: function ($filterTotals) {
        if (this.filter.offset + (this.filter.numToFetch * 1) > $filterTotals.numTransactions) {
            //stop it going past the end.
            return;
        }

        this.filter.offset+= (this.filter.numToFetch * 1);
        this.updateRange();
        $rootScope.$emit('runFilter');
    },

    formatDates: function () {
        if (this.filter.singleDate.in) {
            this.filter.singleDate.inSql = $filter('formatDate')(this.filter.singleDate.in);
        }
        else {
            this.filter.singleDate.inSql = "";
        }
        if (this.filter.singleDate.out) {
            this.filter.singleDate.outSql = $filter('formatDate')(this.filter.singleDate.out);
        }
        else {
            this.filter.singleDate.outSql = "";
        }
        if (this.filter.fromDate.in) {
            this.filter.fromDate.inSql = $filter('formatDate')(this.filter.fromDate.in);
        }
        else {
            this.filter.fromDate.inSql = "";
        }
        if (this.filter.fromDate.out) {
            this.filter.fromDate.outSql = $filter('formatDate')(this.filter.fromDate.out);
        }
        else {
            this.filter.fromDate.outSql = "";
        }
        if (this.filter.toDate.in) {
            this.filter.toDate.inSql = $filter('formatDate')(this.filter.toDate.in);
        }
        else {
            this.filter.toDate.inSql = "";
        }
        if (this.filter.toDate.out) {
            this.filter.toDate.outSql = $filter('formatDate')(this.filter.toDate.out);
        }
        else {
            this.filter.toDate.outSql = "";
        }

        return this.filter;
    },

    getBasicTotals: function () {
        $object.filter = $object.formatDates($object.filter);

        var $url = 'api/filter/basicTotals';

        return $http.post($url, {'filter': $object.filter});
    },

    calculateGraphFigures: function ($graphTotals) {
        var $graphFigures = {
            months: []
        };

        $($graphTotals.monthsTotals).each(function () {
            var $expenses = this.debit * -1;
            var $max = $graphTotals.maxTotal;
            var $num = 500 / $max;

            $graphFigures.months.push({
                incomeHeight: this.credit * $num,
                expensesHeight: $expenses * $num,
                income: this.credit,
                expenses: this.debit,
                month: this.month
            });
        });

        return $graphFigures;
    },
};

FilterRepository.resetFilter();