var Graphs = Vue.component('graphs', {
    template: '#graphs-template',
    data: function () {
        return {
            graphFigures: {months: []},
            a: 4
        };
    },
    components: {},
    filters: {
        numberFilter: function (number, howManyDecimals) {
            return HelpersRepository.numberFilter(number, howManyDecimals);
        }
    },
    computed: {
        // months: function () {
        //   return _.pluck(this.graphFigures, 'months');
        // }
        b: function () {
            return this.a * 2;
        },
        debitForChart: function () {
            var array = _.pluck(this.graphFigures.months, 'expenses');
            return _.map(array, function (num) {
                return num * -1;
            })
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
                this.chart();
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
            var months = _.pluck(this.graphFigures.months, 'month');
            var data = {
                labels: months,
                datasets: [
                    //Debit
                    {
                        data: this.debitForChart,
                        label: "Debit",
                        backgroundColor: "rgba(255,0,0,0.2)",
                        borderColor: "rgba(255,99,132,1)",
                        borderWidth: 1,
                        hoverBackgroundColor: "rgba(255,99,132,0.4)",
                        hoverBorderColor: "rgba(255,99,132,1)",
                    },
                    //Credit
                    {
                        data: _.pluck(this.graphFigures.months, 'income'),
                        label: "Credit",
                        backgroundColor: "rgba(0,255,0,0.2)",
                        borderColor: "rgba(255,99,132,1)",
                        borderWidth: 1,
                        hoverBackgroundColor: "rgba(255,99,132,0.4)",
                        hoverBorderColor: "rgba(255,99,132,1)",
                    }
                ]
            };

            setTimeout(function () {
                new Chart(document.querySelector('#chart').getContext('2d'), {
                    type: 'bar',
                    data: data
                });
            }, 1000);


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
    }
});