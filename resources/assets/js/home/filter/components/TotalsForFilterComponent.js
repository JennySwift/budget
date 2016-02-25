var TotalsForFilter = Vue.component('totals-for-filter', {
    template: '#totals-for-filter-template',
    data: function () {
        return {
            filterTotals: FilterFactory.filterBasicTotals
        };
    },
    components: {},
    methods: {
        listen: function () {
            $(document).on('get-basic-filter-totals', function (event) {
                FilterFactory.getBasicTotals($scope.filter)
                    .then(function (response) {
                        FilterFactory.filterBasicTotals = response.data;
                        $scope.filterTotals = response.data;
                        $rootScope.hideLoading();
                    })
                    .catch(function (response) {
                        $rootScope.responseError(response);
                    })
            });
        }
    },
    props: [
        'show',
        'filter'
    ],
    ready: function () {
        this.listen();
    }
});