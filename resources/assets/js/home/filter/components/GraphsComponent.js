var Graphs = Vue.component('graphs', {
    template: '#graphs-template',
    data: function () {
        return {

        };
    },
    components: {},
    methods: {
        listen: function () {
            $(document).on('get-graph-totals', function (event) {
                $rootScope.showLoading();
                FilterFactory.getGraphTotals(FilterFactory.filter)
                    .then(function (response) {
                        $scope.graphFigures = FilterFactory.calculateGraphFigures(response.data);
                        $rootScope.hideLoading();
                    })
                    .catch(function (response) {
                        $rootScope.responseError(response);
                    })
            });
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.listen();
    }
});