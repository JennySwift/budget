var Graphs = Vue.component('graphs', {
    template: '#graphs-template',
    data: function () {
        return {
            graphFigures: {months: []},
            charts: {
                lineChart: {},
                barChart: {},
            },
            chartsCreated: false,
            chartData: {
                all: {}
            }
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
        debitForChart: function () {
            var array = _.pluck(this.graphFigures.months, 'expenses');
            return _.map(array, function (num) {
                return num * -1;
            })
        },
        negativeTransferTotals: function () {
            var array = _.pluck(this.graphFigures.months, 'negativeTransferTotal');
            return _.map(array, function (num) {
                if (num === 0) {
                    return num;
                }
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
                    month: this.month,
                    balanceFromBeginning: this.balanceFromBeginning,
                    positiveTransferTotal: this.positiveTransferTotal,
                    negativeTransferTotal: this.negativeTransferTotal,
                });
            });

            return graphFigures;
        },

        /**
         *
         */
        chart: function () {
            var months = _.pluck(this.graphFigures.months, 'month');
            this.chartData.all = {
                labels: months,
                datasets: [
                    //Debit
                    {
                        data: this.debitForChart,
                        label: "Debit",
                        backgroundColor: "rgba(255,0,0,0.2)",
                        borderColor: "rgba(255,99,132,1)",
                        borderWidth: 1,
                        hoverBackgroundColor: "rgba(255,0,0,0.4)",
                        hoverBorderColor: "rgba(255,99,132,1)",
                    },
                    //Credit
                    {
                        data: _.pluck(this.graphFigures.months, 'income'),
                        label: "Credit",
                        backgroundColor: "rgba(0,255,0,0.2)",
                        borderColor: "rgba(255,99,132,1)",
                        borderWidth: 1,
                        hoverBackgroundColor: "rgba(0,255,0,0.4)",
                        hoverBorderColor: "rgba(255,99,132,1)",
                    },
                    //Balance from beginning
                    {
                        data: _.pluck(this.graphFigures.months, 'balanceFromBeginning'),
                        label: "Balance",
                        backgroundColor: "rgba(252, 167, 0, .2)",
                        borderColor: "rgba(255,99,132,1)",
                        borderWidth: 1,
                        hoverBackgroundColor: "rgba(252, 167, 0, .4)",
                        hoverBorderColor: "rgba(255,99,132,1)",
                    },
                    //Positive transfers
                    {
                        data: _.pluck(this.graphFigures.months, 'positiveTransferTotal'),
                        label: "Positive transfers",
                        backgroundColor: "rgba(0,255,0,0.6)",
                        borderColor: "rgba(255,99,132,1)",
                        borderWidth: 1,
                        hoverBackgroundColor: "rgba(0,255,0,0.8)",
                        hoverBorderColor: "rgba(255,99,132,1)",
                    },
                    //Negative transfers
                    {
                        data: this.negativeTransferTotals,
                        label: "Negative transfers",
                        backgroundColor: "rgba(255,0,0,0.6)",
                        borderColor: "rgba(255,99,132,1)",
                        borderWidth: 1,
                        hoverBackgroundColor: "rgba(255,0,0,0.8)",
                        hoverBorderColor: "rgba(255,99,132,1)",
                    }
                ]
            };

            if (this.chartsCreated) {
                this.destroyCharts();
            }
            this.createCharts();
        },

        /**
         *
         * @param data
         */
        destroyCharts: function () {
            this.charts.barChart.destroy();
            this.charts.lineChart.destroy();
        },

        /**
         *
         * @param data
         */
        createCharts: function () {
            var that = this;
            setTimeout(function () {
                that.charts.barChart = new Chart(document.querySelector('#bar-chart').getContext('2d'), {
                    type: 'bar',
                    data: that.chartData.all,
                    options: {
                        maintainAspectRatio: false
                    }
                });

                that.charts.lineChart = new Chart(document.querySelector('#line-chart').getContext('2d'), {
                    type: 'line',
                    data: that.chartData.all,
                    options: {
                        maintainAspectRatio: false
                    }
                });

                that.chartsCreated = true;
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