<script id="graphs-template" type="x-template">

    <div id="graphs-component">
        <div class="chart-container">
            <canvas id="bar-chart"></canvas>
        </div>

        <div class="chart-container">
            <canvas id="line-chart"></canvas>
        </div>

        <h3>To show much has been spent on all budgets within the specified date range</h3>
        <div class="chart-container">
            <canvas id="doughnut-chart"></canvas>
        </div>

    </div>

    {{--<div id="graphs">--}}
        {{----}}
        {{--<div v-for="month in graphFigures.months" class="month-container">--}}

            {{--<div id="months">--}}
                {{--<div--}}
                        {{--v-bind:style="{height: month.expensesHeight + 'px'}"--}}
                        {{--id="debit">--}}

                    {{--<div class="span-container">--}}
                        {{--<span v-if="month.expenses < 0" class="badge">@{{ month.expenses | numberFilter 2 }}</span>--}}
                    {{--</div>--}}

                {{--</div>--}}

                {{--<div--}}
                        {{--v-bind:style="{height: month.incomeHeight + 'px'}"--}}
                        {{--id="credit">--}}

                    {{--<div class="span-container">--}}
                        {{--<span v-if="month.income > 0" class="badge">@{{ month.income | numberFilter 2 }}</span>--}}
                    {{--</div>--}}

                {{--</div>--}}
            {{--</div>--}}

            {{--<div>@{{ month.month }}</div>--}}

        {{--</div>--}}

    {{--</div>--}}



</script>