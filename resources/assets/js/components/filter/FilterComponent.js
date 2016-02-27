var Filter = Vue.component('filter', {
    template: '#filter-template',
    data: function () {
        return {
            filterTab: 'show',
            filter: FilterRepository.filter,
            savedFilters: [],
            showFilter: false
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        getSavedFilters: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/savedFilters', function (response) {
                this.savedFilters = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         * I am using the id and a clone, so that the savedFilter
         * doesn't change (with actions such as next/prev button clicks)
         * unless deliberately saved again.
         * @param $savedFilterClone
         */
        chooseSavedFilter: function ($savedFilter) {
            var $preservedSavedFilter = _.findWhere($preservedSavedFilters, {id: $savedFilter.id});
            var $clone = angular.copy($preservedSavedFilter);
            FilterFactory.chooseSavedFilter($clone.filter);
            $scope.filter = FilterFactory.filter;
            $rootScope.$emit('runFilter');
        },

        runFilter: function () {
            $rootScope.$emit('runFilter');
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('toggle-filter', function (event) {
                that.showFilter = !that.showFilter;
            });
        }
    },
    props: [
        'show'
    ],
    ready: function () {
        this.listen();
        this.getSavedFilters();
    }
});



//Doing this because $scope.savedFilters was updating when I didn't want it to.
//If the user hit the prev or next buttons, then used the saved filter again,
//the saved filter was modified and not the original saved filter.
//I think because I set the filter ng-model to the saved filter in the filter factory.
//var $preservedSavedFilters = angular.copy(savedFilters);
//
//
//
//$rootScope.$on('resetFilterInFilterController', function () {
//    $scope.filter = FilterFactory.filter;
//});
//
//$rootScope.$on('runFilter', function (event, data) {
//    $rootScope.$emit('getFilterBasicTotals');
//    if ($scope.tab === 'transactions') {
//        $scope.$emit('filterTransactions', $scope.filter);
//    }
//    else {
//        $scope.$emit('getGraphTotals');
//    }
//});
//
//$rootScope.$on('newSavedFilter', function (event, savedFilter) {
//    $scope.savedFilters.push(savedFilter);
//    $preservedSavedFilters.push(savedFilter);
//});
