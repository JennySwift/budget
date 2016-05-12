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

            var data = {filter: {}};

            if (this.filter) {
                data = {
                    filter: FilterRepository.formatDates(this.filter)
                };
            }

            this.$http.post('/api/filter/graphTotals', data, function (response) {
                this.graphFigures = this.calculateGraphFigures(response);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
         *
         * @param graphTotals
         * @returns {{months: Array}}
         */
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
        chart: function () {
            // var context = $('#chart').getContext('2d');

            var data = {
                labels: ['Jan', 'Feb', 'Mar'],
                datasets: [
                    //For the first line
                    {
                        data: [10, 20, 30],
                        // fillColor: "rgba(...)"
                    },
                    //For another line
                    {
                        data: [10, 20, 30],
                        // fillColor: "rgba(...)"
                    }
                ]
            };

            new Chart(document.querySelector('#chart').getContext('2d'), {
                type: 'bar',
                data: data
            });
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('get-graph-totals', function (event, filter) {
                that.filter = filter;
                that.getGraphTotals();
            });
        }
    },
    props: [

    ],
    ready: function () {
        this.getGraphTotals();
        this.listen();
        this.chart();
    }
});