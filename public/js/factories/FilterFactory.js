app.factory('FilterFactory', function ($http) {
    var $object = {};
    $object.filter_results = {};

    $object.multiSearch = function ($filter, $reset) {
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

        $http.post($url, $data)
            .success(function (response) {
                $object.filter_results = response;
                return $object.filter_results;
            });
        //$object.filter_results = 2;


    };

    return $object;
});