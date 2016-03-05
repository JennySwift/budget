var TotalsForFilter = Vue.component('totals-for-filter', {
    template: '#totals-for-filter-template',
    data: function () {
        return {
            filterTotals: {}
        };
    },
    components: {},
    filters: {
        numberFilter: function (number, howManyDecimals) {
            return HelpersRepository.numberFilter(number, howManyDecimals);
        }
    },
    methods: {

        /**
        * Todo: should be GET not POST
        */
        getBasicFilterTotals: function () {
            this.filter = FilterRepository.formatDates(FilterRepository.filter);

            var data = {
                filter: this.filter
            };

            $.event.trigger('show-loading');
            this.$http.post('/api/filter/basicTotals', data, function (response) {
                this.filterTotals = response;
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
            $(document).on('get-basic-filter-totals', function (event) {
                that.getBasicFilterTotals();
            });
        }
    },
    props: [
        'show',
        'filter'
    ],
    ready: function () {
        this.listen();
    }
});