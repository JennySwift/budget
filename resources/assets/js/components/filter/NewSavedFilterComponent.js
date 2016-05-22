var NewSavedFilter = Vue.component('new-saved-filter', {
    template: '#new-saved-filter-template',
    data: function () {
        return {

        };
    },
    components: {},
    methods: {

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
                SavedFiltersRepository.addSavedFilter(response.data);
                $.event.trigger('provide-feedback', ['Filter saved', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

    },
    props: [
        'filter'
    ],
    ready: function () {

    }
});
