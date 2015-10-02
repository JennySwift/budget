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


    $object.formatDates = function () {
        if (this.filter.single_date.in) {
            this.filter.single_date.in = $filter('formatDate')(this.filter.single_date.in);
        }
        else {
            this.filter.single_date.in = "";
        }
        if (this.filter.single_date.out) {
            this.filter.single_date.out = $filter('formatDate')(this.filter.single_date.out);
        }
        else {
            this.filter.single_date.out = "";
        }
        if (this.filter.from_date.in) {
            this.filter.from_date.in = $filter('formatDate')(this.filter.from_date.in);
        }
        else {
            this.filter.from_date.in = "";
        }
        if (this.filter.from_date.out) {
            this.filter.from_date.out = $filter('formatDate')(this.filter.from_date.out);
        }
        else {
            this.filter.from_date.out.sql = "";
        }
        if (this.filter.to_date.in) {
            this.filter.to_date.in = $filter('formatDate')(this.filter.to_date.in);
        }
        else {
            this.filter.to_date.in = "";
        }
        if (this.filter.to_date.out) {
            this.filter.to_date.out = $filter('formatDate')(this.filter.to_date.out);
        }
        else {
            this.filter.to_date.out = "";
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