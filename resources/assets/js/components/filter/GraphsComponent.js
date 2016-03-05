var Graphs = Vue.component('graphs', {
    template: '#graphs-template',
    data: function () {
        return {
            graphFigures: {}
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
        *
        */
        getGraphTotals: function () {
            $.event.trigger('show-loading');

            var data = {
                filter: FilterRepository.formatDates(FilterRepository.filter)
            };

            this.$http.post('/api/filter/graphTotals', data, function (response) {
                this.graphFigures = FilterRepository.calculateGraphFigures(response);
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
            $(document).on('get-graph-totals', function (event) {
               that.getGraphTotals();
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