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
