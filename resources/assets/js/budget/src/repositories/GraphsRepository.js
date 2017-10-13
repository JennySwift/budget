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
        this.state.charts.lineChart = new Chart(document.querySelector('#line-chart').getContext('2d'), {
            type: 'line',
            data: GraphsRepository.state.chartData.lineAndBar,
            options: {
                maintainAspectRatio: false
            }
        });
    },

    /**
     *
     */
    createBarChart: function () {
        this.state.charts.barChart = new Chart(document.querySelector('#bar-chart').getContext('2d'), {
            type: 'bar',
            data: GraphsRepository.state.chartData.lineAndBar,
            options: {
                maintainAspectRatio: false
            }
        });
    },

    /**
     *
     */
    createDoughnutChart: function () {
        this.state.charts.doughnutChart = new Chart(document.querySelector('#doughnut-chart').getContext('2d'), {
            type: 'doughnut',
            data: GraphsRepository.state.chartData.doughnut,
            options: {
                maintainAspectRatio: false
            }
        });
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
    }

}