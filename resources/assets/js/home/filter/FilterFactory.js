app.factory('FilterFactory', function ($http, $rootScope, $filter) {
    var $object = {};

    $object.resetFilter = function () {
        $object.filter = {

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
            single_date: {
                in: '',
                out: ''
            },
            from_date: {
                in: '',
                out: ''
            },
            to_date: {
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
            num_to_fetch: 30,
            display_from: 1,
            display_to: 30
        };

        this.filter = $object.filter;

        $rootScope.$emit('resetFilterInFilterController');

        return $object.filter;
    };

    $object.resetFilter();

    $object.filterBasicTotals = filterBasicTotals;

    /**
     * Updates filter.display_from and filter.display_to values
     */
    $object.updateRange = function ($numToFetch) {
        if ($numToFetch) {
            this.filter.num_to_fetch = $numToFetch;
        }

        this.filter.display_from = this.filter.offset + 1;
        this.filter.display_to = this.filter.offset + (this.filter.num_to_fetch * 1);
    };

    //Todo: I might not need some of this code (not allowing offset to be less than 0)
    // todo: since I disabled the button if that is the case
    $object.prevResults = function () {
        //make it so the offset cannot be less than 0.
        if (this.filter.offset - this.filter.num_to_fetch < 0) {
            this.filter.offset = 0;
        }
        else {
            this.filter.offset-= (this.filter.num_to_fetch * 1);
            this.updateRange();
            $rootScope.$emit('runFilter');
        }
    };

    $object.nextResults = function ($filterTotals) {
        if (this.filter.offset + (this.filter.num_to_fetch * 1) > $filterTotals.numTransactions) {
            //stop it going past the end.
            return;
        }

        this.filter.offset+= (this.filter.num_to_fetch * 1);
        this.updateRange();
        $rootScope.$emit('runFilter');
    };

    $object.formatDates = function () {
        if (this.filter.single_date.in) {
            this.filter.single_date.inSql = $filter('formatDate')(this.filter.single_date.in);
        }
        else {
            this.filter.single_date.inSql = "";
        }
        if (this.filter.single_date.out) {
            this.filter.single_date.outSql = $filter('formatDate')(this.filter.single_date.out);
        }
        else {
            this.filter.single_date.outSql = "";
        }
        if (this.filter.from_date.in) {
            this.filter.from_date.inSql = $filter('formatDate')(this.filter.from_date.in);
        }
        else {
            this.filter.from_date.inSql = "";
        }
        if (this.filter.from_date.out) {
            this.filter.from_date.outSql = $filter('formatDate')(this.filter.from_date.out);
        }
        else {
            this.filter.from_date.outSql = "";
        }
        if (this.filter.to_date.in) {
            this.filter.to_date.inSql = $filter('formatDate')(this.filter.to_date.in);
        }
        else {
            this.filter.to_date.inSql = "";
        }
        if (this.filter.to_date.out) {
            this.filter.to_date.outSql = $filter('formatDate')(this.filter.to_date.out);
        }
        else {
            this.filter.to_date.outSql = "";
        }

        return this.filter;
    };

    $object.getTransactions = function () {
        $object.filter = $object.formatDates($object.filter);

        var $url = 'api/filter/transactions';

        return $http.post($url, {'filter': $object.filter});
    };

    $object.getBasicTotals = function () {
        $object.filter = $object.formatDates($object.filter);

        var $url = 'api/filter/basicTotals';

        return $http.post($url, {'filter': $object.filter});
    };

    $object.getGraphTotals = function () {
        $object.filter = $object.formatDates($object.filter);

        var $url = 'api/filter/graphTotals';

        return $http.post($url, {'filter': $object.filter});
    };

    $object.calculateGraphFigures = function ($graphTotals) {
        var $graphFigures = {
            months: []
        };

        $($graphTotals.monthsTotals).each(function () {
            var $expenses = this.expenses * -1;
            var $max = $graphTotals.maxTotal;
            var $num = 500 / $max;

            $graphFigures.months.push({
                incomeHeight: this.income * $num,
                expensesHeight: $expenses * $num,
                income: this.income,
                expenses: this.expenses,
                month: this.month
            });
        });

        return $graphFigures;
    };

    return $object;
});