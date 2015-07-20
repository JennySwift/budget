app.factory('FilterFactory', function ($http) {
    var $object = {};
    //$object.filter_results = {};
    $object.filter = {
        budget: "all",
        total: "",
        types: [],
        accounts: [],
        single_date: "",
        from_date: "",
        to_date: "",
        description: "",
        merchant: "",
        tags: [],
        reconciled: "any",
        offset: 0,
        num_to_fetch: 20
    };

    $object.multiSearch = function ($filter) {
        $object.filter = $filter;

        if ($filter.single_date) {
            $filter.single_date_sql = Date.parse($filter.single_date).toString('yyyy-MM-dd');
        }
        if ($filter.from_date) {
            $filter.from_date_sql = Date.parse($filter.from_date).toString('yyyy-MM-dd');
        }
        if ($filter.to_date) {
            $filter.to_date_sql = Date.parse($filter.to_date).toString('yyyy-MM-dd');
        }

        var $url = 'select/filter';
        var $data = {
            description: 'filter',
            filter: $filter
        };

        return $http.post($url, $data);
            //.success(function (response) {
            //    $object.filter_results = response;
            //    return $object.filter_results;
            //});
        //$object.filter_results = 2;


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
        if ($data.totals) {
            //The non filter totals
            $object.totals = $data.totals;
        }
    };

    return $object;
});