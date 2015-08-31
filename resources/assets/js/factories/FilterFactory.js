app.factory('FilterFactory', function ($http) {
    var $object = {};

    $object.resetFilter = function () {
        $object.filter = {
            budget: {
                in: "all",
                out: ""
            },
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
            tags: {
                in: {
                    and: [],
                    or: []
                },
                out: []
            },
            reconciled: "any",
            offset: 0,
            num_to_fetch: 20
        };
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

    $object.multiSearch = function ($filter) {
        $object.filter = $object.formatDates($filter);

        var $url = 'api/select/filter';
        var $data = {
            description: 'filter',
            filter: $filter
        };

        return $http.post($url, $data);
    };

    /**
     * For displaying the filtered transactions
     * and the filter totals
     * and the non-filter totals on the page
     * todo: maybe this should be in some totals factory
     * @param $data
     */
    $object.updateDataForControllers = function ($data) {
        if ($data.filter_results) {
            //This includes filtered transactions as well as filter totals
            $object.filter_results = $data.filter_results;
        }
        //if ($data.totals) {
        //    //The non filter totals
        //    $object.totals = $data.totals;
        //}
        if ($data.basicTotals) {
            $object.basicTotals = $data.basicTotals;
        }
        if ($data.fixedBudgetTotals) {
            $object.fixedBudgetTotals = $data.fixedBudgetTotals;
        }
        if ($data.flexBudgetTotals) {
            $object.flexBudgetTotals = $data.flexBudgetTotals;
        }
        if ($data.remainingBalance) {
            $object.remainingBalance = $data.remainingBalance;
        }
    };

    return $object;
});