var SavedFilters = Vue.component('saved-filters', {
    template: '#saved-filters-template',
    data: function () {
        return {
            savedFilters: [],
            selectedSavedFilter: {}
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
        chooseSavedFilter: function () {
            //var preservedSavedFilter = _.findWhere(preservedSavedFilters, {id: this.selectedSavedFilter.id});
            //var clone = JSON.parse(JSON.stringify(preservedSavedFilter));
            //this.filter = clone.filter;
            //$.event.trigger('set-filter-in-toolbar');
            this.filter = this.selectedSavedFilter.filter;
            this.runFilter(this.filter);
        },

        /**
         *
         */
        insertSavedFilter: function () {
            var name = prompt('Please name your filter');

            $.event.trigger('show-loading');

            var data = {
                name: name,
                filter: this.filter
            };

            this.$http.post('/api/savedFilters', data, function (response) {
                    this.savedFilters.push(response);
                    $.event.trigger('new-saved-filter');
                    $.event.trigger('provide-feedback', ['Filter created', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('saved-filter-created', function (event, savedFilter) {
                //Doing this because $scope.savedFilters was updating when I didn't want it to.
                //If the user hit the prev or next buttons, then used the saved filter again,
                //the saved filter was modified and not the original saved filter.
                //I think because I set the filter ng-model to the saved filter in the filter factory.
                var preservedSavedFilters = JSON.parse(JSON.stringify(that.savedFilters));;
                that.savedFilters.push(savedFilter);
                preservedSavedFilters.push(savedFilter);
            });
        }
    },
    props: [
        'runFilter',
        'filter'
    ],
    ready: function () {
        this.getSavedFilters();
        this.listen();
    }
});
