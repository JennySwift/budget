var Graphs = Vue.component('graphs', {
    template: '#graphs-template',
    data: function () {
        return {
            graphFigures: {months: []},
            charts: {
                lineChart: {},
                barChart: {},
                doughnutChart: {}
            },
            chartsCreated: false,
            chartData: {
                all: {},
                doughnut: {}
            },
            filterRepository: FilterRepository.state
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
        filter: function () {
            return this.filterRepository.filter;
        },
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
        },
        spentOnBudgets: function () {
            var array = _.pluck(this.chartData.doughnut, 'spentInDateRange');
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
                this.filter = FilterRepository.formatDates(this.filter);
                data = {
                    filter: this.filter
                };
            }

            this.$http.post('/api/filter/graphTotals', data, function (response) {
                this.graphFigures = this.calculateGraphFigures(response);
                this.chart();
                this.getDoughnutChartData();
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
        populateDoughnutChartData: function () {
            this.chartData.doughnut = {
                labels: _.pluck(this.chartData.doughnut, 'name'),
                datasets: [
                    //Spent on budgets in date range
                    {
                        data: this.spentOnBudgets,
                        label: "Budgets",
                        backgroundColor: [
                            'rgba(0,0,0,.4)',
                            'rgba(255,0,0,.4)',
                            'rgba(0,255,0,.4)',
                            'rgba(0,0,255,.4)',
                            'rgba(0,255,255,.4)',
                        ],
                        hoverBackgroundColor: [
                            'rgba(0,0,0,.6)',
                            'rgba(255,0,0,.6)',
                            'rgba(0,255,0,.6)',
                            'rgba(0,0,255,.6)',
                            'rgba(0,255,255,.6)',
                        ],
                    },
                ]
            }
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
            this.charts.doughnutChart.destroy();
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

                that.charts.doughnutChart = new Chart(document.querySelector('#doughnut-chart').getContext('2d'), {
                    type: 'doughnut',
                    data: that.chartData.doughnut,
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
        getDoughnutChartData: function () {
            var from = '';
            var to = '';
            if (this.filter) {
                from = this.filter.fromDate.inSql;
                to = this.filter.toDate.inSql;
            }

            $.event.trigger('show-loading');
            this.$http.get('/api/totals/spentOnBudgets?from=' + from + '&to=' + to, function (response) {
                this.chartData.doughnut = response;
                this.populateDoughnutChartData();
                $.event.trigger('hide-loading');
            })
            .error(function (data, status, response) {
                HelpersRepository.handleResponseError(data, status, response);
            });
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('get-graph-totals', function (event) {
                that.getGraphTotals();
                that.getDoughnutChartData();
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