<script id="graphs-page-template" type="x-template">

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

</script>