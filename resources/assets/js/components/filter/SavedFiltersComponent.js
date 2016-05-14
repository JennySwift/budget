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
         *
         */
        chooseSavedFilter: function () {
            this.filter = FilterRepository.setFields(this.filter, this.selectedSavedFilter.filter);
            this.runFilter(this.filter);
        },

        /**
        *
        */
        deleteSavedFilter: function (savedFilter) {
            if (confirm("Are you sure?")) {
                $.event.trigger('show-loading');
                this.$http.delete('/api/savedFilters/' + savedFilter.id, function (response) {
                    // SavedFiltersRepository.deleteSavedFilter(this.selectedSavedFilter);
                    var index = HelpersRepository.findIndexById(this.savedFilters, savedFilter.id);
                    this.savedFilters = _.without(this.savedFilters, this.savedFilters[index]);
                    $.event.trigger('provide-feedback', ['SavedFilter deleted', 'success']);
                    $.event.trigger('hide-loading');
                })
                .error(function (data, status, response) {
                    HelpersRepository.handleResponseError(data, status, response);
                });
            }
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('saved-filter-created', function (event, savedFilter) {
                that.savedFilters.push(savedFilter);
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
