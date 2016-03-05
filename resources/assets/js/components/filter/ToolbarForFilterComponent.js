var ToolbarForFilter = Vue.component('toolbar-for-filter', {
    template: '#toolbar-for-filter-template',
    data: function () {
        return {
            filter: FilterRepository.filter
        };
    },
    components: {},
    methods: {

        /**
         * 
         */
        resetFilter: function () {
            FilterRepository.resetFilter();
            this.filter = FilterRepository.filter;
            $.event.trigger('run-filter');
        },

        /**
         *
         */
        changeNumToFetch: function () {
            FilterRepository.updateRange(this.filter.numToFetch);
            $.event.trigger('run-filter');
        },

        /**
         *
         */
        prevResults: function () {
            FilterRepository.prevResults();
        },

        /**
         *
         */
        nextResults: function () {
            FilterRepository.nextResults(this.filterTotals.numTransactions);
        },


        /**
        *
        */
        insertFilter: function () {
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
            $(document).on('set-filter-in-toolbar', function (event) {
                that.filter = FilterRepository.filter;
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
//this.$watch('filterFactory.filterBasicTotals', function (newValue, oldValue, scope) {
//    this.filterTotals = newValue;
//});