import helpers from './Helpers'
import FilterRepository from './FilterRepository'
require('chart.js');
export default {

    state: {
        lineChartCreated: false,
        barChartCreated: false,
        doughnutChartCreated: false,

        chartData: {
            lineAndBar: {},
            doughnut: {}
        },

        charts: {
            lineChart: {},
            barChart: {},
            doughnutChart: {}
        },
    },

    /**
     *
     * @param chartType
     */
    setChartCreated: function (chartType) {
        this.state[chartType + 'ChartCreated'] = true;
    },

    /**
     *
     */
    createLineChart: function () {
        // var that = this;
        // setTimeout(function () {
        //     that.state.charts.lineChart = new Chart(document.querySelector('#line-chart').getContext('2d'), {
        //         type: 'line',
        //         data: that.state.chartData.lineAndBar,
        //         options: {
        //             maintainAspectRatio: false
        //         }
        //     });
        //     that.setChartCreated('line');
        // }, 1000);
    },

    /**
     *
     */
    createBarChart: function () {
        // var that = this;
        // setTimeout(function () {
        //     that.state.charts.barChart = new Chart(document.querySelector('#bar-chart').getContext('2d'), {
        //         type: 'bar',
        //         data: that.state.chartData.lineAndBar,
        //         options: {
        //             maintainAspectRatio: false
        //         }
        //     });
        //     that.setChartCreated('bar');
        // }, 1000);

    },

    /**
     *
     */
    createDoughnutChart: function () {
        // var that = this;
        // setTimeout(function () {
        //     that.state.charts.doughnutChart = new Chart(document.querySelector('#doughnut-chart').getContext('2d'), {
        //         type: 'doughnut',
        //         data: that.state.chartData.doughnut,
        //         options: {
        //             maintainAspectRatio: false
        //         }
        //     });
        //     that.setChartCreated('doughnut');
        // }, 1000);
    },

    /**
     *
     * @param data
     */
    setLineAndBarChartData: function (data) {
        this.state.chartData.lineAndBar = data;
    },

    /**
     *
     * @param data
     */
    setDoughnutChartData: function (data) {
        this.state.chartData.doughnut = data;
    },

    /**
     *
     * @param chartType
     */
    destroyChart: function (chartType) {
        // this.state.charts[chartType + 'Chart'].destroy();
        $('#' + chartType + '-chart').remove();
        $('#' + chartType + '-chart-container').append('<canvas id="' + chartType + '-chart"></canvas>');
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
                this.setLineAndBarChartData(stuff);

                //Destroy and create line chart
                if (this.state.lineChartCreated) {
                    this.destroyChart('line');
                }
                this.createLineChart();

                //Destroy and create bar chart
                if (this.state.barChartCreated) {
                    this.destroyChart('bar');
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
                this.setDoughnutChartData(this.populateDoughnutChartData(response));

                if (this.state.doughnutChartCreated) {
                    this.destroyChart('doughnut');
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
        var array = _.map(data, 'spentInDateRange');
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
            labels: _.map(data, 'name'),
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
     * @returns {{labels, datasets: *[]}}
     */
    populateLineAndBarChartData: function (data) {
        var months = _.map(data.monthTotals, 'month');
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
                    data: _.map(data.monthTotals, 'credit'),
                    label: "Credit",
                    backgroundColor: "rgba(0,255,0,0.2)",
                    borderColor: "rgba(255,99,132,1)",
                    borderWidth: 1,
                    hoverBackgroundColor: "rgba(0,255,0,0.4)",
                    hoverBorderColor: "rgba(255,99,132,1)",
                },

                //Balance from beginning
                {
                    data: _.map(data.monthTotals, 'balanceFromBeginning'),
                    label: "Balance",
                    backgroundColor: "rgba(252, 167, 0, .2)",
                    borderColor: "rgba(255,99,132,1)",
                    borderWidth: 1,
                    hoverBackgroundColor: "rgba(252, 167, 0, .4)",
                    hoverBorderColor: "rgba(255,99,132,1)",
                },

                //Positive transfers
                {
                    data: _.map(data.monthTotals, 'positiveTransferTotal'),
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
    },

    /**
     *
     * @param data
     * @returns {*}
     */
    populateNegativeTransferTotals: function (data) {
        var array = _.map(data, 'negativeTransferTotal');
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
        var array = _.map(data, 'debit');
        return _.map(array, function (num) {
            return num * -1;
        })
    },

}