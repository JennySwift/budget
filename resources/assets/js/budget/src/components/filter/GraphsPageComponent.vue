<template>
    <div id="graphs-component">
        <div id="bar-chart-container" class="chart-container">
            <canvas id="bar-chart"></canvas>
        </div>

        <div id="line-chart-container" class="chart-container">
            <canvas id="line-chart"></canvas>
        </div>

        <h3>To show much has been spent on all budgets within the specified date range</h3>
        <div id="doughnut-chart-container" class="chart-container">
            <canvas id="doughnut-chart"></canvas>
        </div>

        <filter
            :show="show"
            :budgets="budgets"
        ></filter>

    </div>
</template>

<script>
    import FilterRepository from '../../repositories/FilterRepository'
    import GraphsRepository from '../../repositories/GraphsRepository'
    import Helpers from '../../repositories/Helpers'
    export default {
        data: function () {
            return {
                filterRepository: FilterRepository.state,
                graphsRepository: GraphsRepository.state
            };
        },
        components: {},
        filters: {
            numberFilter: function (number, howManyDecimals) {
                return Helpers.numberFilter(number, howManyDecimals);
            }
        },
        computed: {
            graphDataLoaded: function () {
                return this.graphsRepository.lineChartCreated && this.graphsRepository.barChartCreated && this.graphsRepository.doughnutChartCreated;
            },
            filter: function () {
                return this.filterRepository.filter;
            }
        },
        methods: {

            /**
             *
             */
            getAllGraphData: function () {
                this.getDoughnutChartData();
                this.getLineAndBarChartData();
            },

            /**
             *
             * @param data
             * @returns {*}
             */
            populateNegativeTransferTotals: function (data) {
                var array = _.pluck(data, 'negativeTransferTotal');
                return _.map(array, function (num) {
                    if (num === 0) {
                        return num;
                    }
                    return num * -1;
                })
            },

            /**
             *
             * @returns {*}
             */
            populateDebit: function (data) {
                var array = _.pluck(data, 'debit');
                return _.map(array, function (num) {
                    return num * -1;
                })
            },

            /**
             *
             */
            getLineAndBarChartData: function () {
                var data = {
                    filter: FilterRepository.formatDates()
                };

                helpers.post({
                    url: '/api/filter/graphTotals',
                    data: data,
                    callback: function (response) {
                        var stuff = this.populateLineAndBarChartData(response);
                        GraphsRepository.setLineAndBarChartData(stuff);

                        //Destroy and create line chart
                        if (this.graphsRepository.lineChartCreated) {
                            GraphsRepository.destroyChart('line');
                        }
                        this.createLineChart();

                        //Destroy and create bar chart
                        if (this.graphsRepository.barChartCreated) {
                            GraphsRepository.destroyChart('bar');
                        }
                        this.createBarChart();
                    }.bind(this)
                });
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

                helpers.get({
                    url: '/api/totals/spentOnBudgets?from=' + from + '&to=' + to,
                    storeProperty: 'dOubs',
                    loadedProperty: 'dOubsLoaded',
                    callback: function (response) {
                        GraphsRepository.setDoughnutChartData(this.populateDoughnutChartData(response));

                        if (this.graphsRepository.doughnutChartCreated) {
                            GraphsRepository.destroyChart('doughnut');
                        }
                        this.createDoughnutChart();
                    }.bind(this)
                });
            },

            /**
             *
             * @param data
             * @returns {*}
             */
            calculateSpentOnBudgets: function (data) {
                var array = _.pluck(data, 'spentInDateRange');
                return _.map(array, function (num) {
                    if (num === 0) {
                        return num;
                    }
                    return num * -1;
                });
            },

            /**
             *
             * @param data
             */
            populateDoughnutChartData: function (data) {
                return {
                    labels: _.pluck(data, 'name'),
                    datasets: [
                        //Spent on budgets in date range
                        {
                            data: this.calculateSpentOnBudgets(data),
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
            createLineChart: function () {
                setTimeout(function () {
                    GraphsRepository.createLineChart();
                    GraphsRepository.setChartCreated('line');
                }, 1000);
            },

            /**
             *
             */
            createBarChart: function () {
                setTimeout(function () {
                    GraphsRepository.createBarChart();
                    GraphsRepository.setChartCreated('bar');
                }, 1000);

            },

            /**
             *
             */
            createDoughnutChart: function () {
                setTimeout(function () {
                    GraphsRepository.createDoughnutChart();
                    GraphsRepository.setChartCreated('doughnut');
                }, 1000);
            },

            /**
             *
             * @returns {{labels, datasets: *[]}}
             */
            populateLineAndBarChartData: function (data) {
                var months = _.pluck(data.monthTotals, 'month');
                return {
                    labels: months,
                    datasets: [

                        //Debit
                        {
                            data: this.populateDebit(data.monthTotals),
                            label: "Debit",
                            backgroundColor: "rgba(255,0,0,0.2)",
                            borderColor: "rgba(255,99,132,1)",
                            borderWidth: 1,
                            hoverBackgroundColor: "rgba(255,0,0,0.4)",
                            hoverBorderColor: "rgba(255,99,132,1)",
                        },

                        //Credit
                        {
                            data: _.pluck(data.monthTotals, 'credit'),
                            label: "Credit",
                            backgroundColor: "rgba(0,255,0,0.2)",
                            borderColor: "rgba(255,99,132,1)",
                            borderWidth: 1,
                            hoverBackgroundColor: "rgba(0,255,0,0.4)",
                            hoverBorderColor: "rgba(255,99,132,1)",
                        },

                        //Balance from beginning
                        {
                            data: _.pluck(data.monthTotals, 'balanceFromBeginning'),
                            label: "Balance",
                            backgroundColor: "rgba(252, 167, 0, .2)",
                            borderColor: "rgba(255,99,132,1)",
                            borderWidth: 1,
                            hoverBackgroundColor: "rgba(252, 167, 0, .4)",
                            hoverBorderColor: "rgba(255,99,132,1)",
                        },

                        //Positive transfers
                        {
                            data: _.pluck(data.monthTotals, 'positiveTransferTotal'),
                            label: "Positive transfers",
                            backgroundColor: "rgba(0,255,0,0.6)",
                            borderColor: "rgba(255,99,132,1)",
                            borderWidth: 1,
                            hoverBackgroundColor: "rgba(0,255,0,0.8)",
                            hoverBorderColor: "rgba(255,99,132,1)",
                        },

                        //Negative transfers
                        {
                            data: this.populateNegativeTransferTotals(data.monthTotals),
                            label: "Negative transfers",
                            backgroundColor: "rgba(255,0,0,0.6)",
                            borderColor: "rgba(255,99,132,1)",
                            borderWidth: 1,
                            hoverBackgroundColor: "rgba(255,0,0,0.8)",
                            hoverBorderColor: "rgba(255,99,132,1)",
                        }
                    ]
                };
            }
        },
        props: [
            'show'
        ],
        events: {
            'get-graph-data': function () {
                this.getAllGraphData();
            }
        },
        mounted: function () {

        }
    }
</script>