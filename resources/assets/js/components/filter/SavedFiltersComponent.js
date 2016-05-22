var SavedFilters = Vue.component('saved-filters', {
    template: '#saved-filters-template',
    data: function () {
        return {
            savedFiltersRepository: SavedFiltersRepository.state,
            selectedSavedFilter: {},
        };
    },
    components: {},
    computed: {
        savedFilters: function () {
            return this.savedFiltersRepository.savedFilters;
        }
    },
    methods: {

        /**
         *
         */
        chooseSavedFilter: function () {
            FilterRepository.setFields(this.selectedSavedFilter.filter);
            this.runFilter();
        },

        /**
        *
        */
        deleteSavedFilter: function (savedFilter) {
            $.event.trigger('show-loading');
            this.$http.delete('/api/savedFilters/' + savedFilter.id, function (response) {
                SavedFiltersRepository.deleteSavedFilter(this.selectedSavedFilter);
                $.event.trigger('provide-feedback', ['SavedFilter deleted', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (data, status, response) {
                HelpersRepository.handleResponseError(data, status, response);
            });
        }
    },
    props: [
        'runFilter'
    ],
    ready: function () {
        
    }
});
