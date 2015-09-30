app.factory('FilterFactory', function ($http) {
    var $object = {};

    $object.test = 1;

    $object.updateTest = function ($newValue) {
        $object.test = $newValue;
        return $object.test;
    };

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
                in: {
                    user: "",
                    sql: ""
                },
                out: {
                    user: "",
                    sql: ""
                }
            },
            from_date: {
                in: {
                    user: "",
                    sql: ""
                },
                out: {
                    user: "",
                    sql: ""
                }
            },
            to_date: {
                in: {
                    user: "",
                    sql: ""
                },
                out: {
                    user: "",
                    sql: ""
                }
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
        return $object.filter;
    };

    $object.resetFilter();

    $object.formatDates = function ($filter) {
        if ($filter.single_date.in.user) {
            $filter.single_date.in.sql = Date.parse($filter.single_date.in.user).toString('yyyy-MM-dd');
        }
        else {
            $filter.single_date.in.sql = "";
        }
        if ($filter.single_date.out.user) {
            $filter.single_date.out.sql = Date.parse($filter.single_date.out.user).toString('yyyy-MM-dd');
        }
        else {
            $filter.single_date.out.sql = "";
        }
        if ($filter.from_date.in.user) {
            $filter.from_date.in.sql = Date.parse($filter.from_date.in.user).toString('yyyy-MM-dd');
        }
        else {
            $filter.from_date.in.sql = "";
        }
        if ($filter.from_date.out.user) {
            $filter.from_date.out.sql = Date.parse($filter.from_date.out.user).toString('yyyy-MM-dd');
        }
        else {
            $filter.from_date.out.sql = "";
        }
        if ($filter.to_date.in.user) {
            $filter.to_date.in.sql = Date.parse($filter.to_date.in.user).toString('yyyy-MM-dd');
        }
        else {
            $filter.to_date.in.sql = "";
        }
        if ($filter.to_date.out.user) {
            $filter.to_date.out.sql = Date.parse($filter.to_date.out.user).toString('yyyy-MM-dd');
        }
        else {
            $filter.to_date.out.sql = "";
        }

        return $filter;
    };

    $object.getTransactions = function ($filter) {
        $object.filter = $object.formatDates($filter);

        var $url = 'api/filter/transactions';

        return $http.post($url, {'filter': $filter});
    };

    $object.getBasicTotals = function ($filter) {
        $object.filter = $object.formatDates($filter);

        var $url = 'api/filter/basicTotals';

        return $http.post($url, {'filter': $filter});
    };

    $object.getGraphTotals = function ($filter) {
        $object.filter = $object.formatDates($filter);

        var $url = 'api/filter/graphTotals';

        return $http.post($url, {'filter': $filter});
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