var ToolbarForFilter = Vue.component('toolbar-for-filter', {
    template: '#toolbar-for-filter-template',
    data: function () {
        return {
            filter: FilterRepository.filter
        };
    },
    components: {},
    methods: {
        resetFilter: function () {
            FilterFactory.resetFilter();
            $scope.filter = FilterFactory.filter;
            $rootScope.$emit('runFilter');
        },

        changeNumToFetch: function () {
            FilterFactory.updateRange($scope.filter.num_to_fetch);
            $rootScope.$emit('runFilter');
        },

        prevResults: function () {
            FilterFactory.prevResults();
        },

        nextResults: function () {
            FilterFactory.nextResults($scope.filterTotals.numTransactions);
        },

        saveFilter: function () {
            var $name = prompt('Please name your filter');
            $rootScope.showLoading();
            FilterFactory.saveFilter($name)
                .then(function (response) {
                    $rootScope.$emit('newSavedFilter', response.data.data);
                    $rootScope.$broadcast('provideFeedback', 'Filter saved');
                    $rootScope.hideLoading();
                })
                .catch(function (response) {
                    $rootScope.responseError(response);
                });
        },

        listen: function () {
            var that = this;
            $(document).on('set-filter-in-toolbar', function (event) {
                that.filter = FilterFactory.filter;
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

//
//$scope.$watch('filterFactory.filterBasicTotals', function (newValue, oldValue, scope) {
//    $scope.filterTotals = newValue;
//});