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
                this.graphFigures = this.calculateGraphFigures(response);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        calculateGraphFigures: function (graphTotals) {
            var graphFigures = {
                months: []
            };

            $(graphTotals.monthsTotals).each(function () {
                var expenses = this.debit * -1;
                var max = graphTotals.maxTotal;
                var num = 500 / max;

                graphFigures.months.push({
                    incomeHeight: this.credit * num,
                    expensesHeight: expenses * num,
                    income: this.credit,
                    expenses: this.debit,
                    month: this.month
                });
            });

            return graphFigures;
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