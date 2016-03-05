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
         * @param savedFilter
         */
        chooseSavedFilter: function (savedFilter) {
            var preservedSavedFilter = _.findWhere(preservedSavedFilters, {id: savedFilter.id});
            var clone = JSON.parse(JSON.stringify(preservedSavedFilter));
            FilterRepository.chooseSavedFilter(clone.filter);
            this.filter = FilterRepository.filter;
            $.event.trigger('run-filter');
        },

        /**
         * 
         */
        runFilter: function () {
            $.event.trigger('run-filter');
        },

        /**
         *
         */
        listen: function () {
            var that = this;

            $(document).on('toggle-filter', function (event) {
                that.showFilter = !that.showFilter;
            });

            $(document).on('run-filter', function (event, data) {
                $.event.trigger('get-basic-filter-totals');
                if (this.tab === 'transactions') {
                    $.event.trigger('filter-transactions', [that.filter]);
                }
                else {
                    $.event.trigger('get-graph-totals');
                }
            });

            $(document).on('reset-filter', function (event) {
                that.filter = FilterRepository.filter;
            });

            $(document).on('saved-filter-created', function (event) {
                //Doing this because $scope.savedFilters was updating when I didn't want it to.
                //If the user hit the prev or next buttons, then used the saved filter again,
                //the saved filter was modified and not the original saved filter.
                //I think because I set the filter ng-model to the saved filter in the filter factory.
                var preservedSavedFilters = JSON.parse(JSON.stringify(savedFilters));;
                this.savedFilters.push(savedFilter);
                preservedSavedFilters.push(savedFilter);
            });
            
        }
    },
    props: [
        'show',
        'budgets',
        'tab'
    ],
    ready: function () {
        this.listen();
        this.getSavedFilters();
    }
});
