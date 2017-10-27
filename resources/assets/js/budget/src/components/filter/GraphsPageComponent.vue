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

        <filter></filter>

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

        },
        props: [

        ],
        events: {

        },
        mounted: function () {

        }
    }
</script>

<style lang="scss" type="text/scss">
    @import '../../../../../sass/variables';
    #graphs-component {
        flex-grow: 1;
        max-width: calc(100% - 40px);
        padding: 40px;
        border: 1px solid #777;
        border-radius: 4px;
        .chart-container, .chart-container {
            height: 335px;
            max-width: 100%;
        }
    }

    #graphs {
        display: flex;
        //    flex-wrap: wrap;
        overflow: scroll;
        max-width: 800px;
        .month-container {
            margin: 0px 10px;
            #months {
                display: flex;
                align-items: flex-end;
                border: 1px solid #777;
                height: 500px;
                #credit {
                    background: $success;
                }
                #debit {
                    background: $danger;
                }
                #credit, #debit {
                    width: 30px;
                    position: relative;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    .span-container {
                        transform: rotate(90deg);
                    }
                }
            }
        }


    }

</style>