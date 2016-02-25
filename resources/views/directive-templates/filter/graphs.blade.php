<script type="text/v-template" id="graphs-template">

    <div id="graphs">

        <div v-repeat="month in graphFigures.months" class="month-container">

            <div id="months">
                <div
                        v-bind:style="{height: month.expensesHeight + 'px'}"
                        id="debit">

                    <div class="span-container">
                        <span v-if="month.expenses < 0" class="badge">[[month.expenses | number:2]]</span>
                    </div>

                </div>

                <div
                        v-bind:style="{height: month.incomeHeight + 'px'}"
                        id="credit">

                    <div class="span-container">
                        <span v-if="month.income > 0" class="badge">[[month.income | number:2]]</span>
                    </div>

                </div>
            </div>

            <div>[[month.month]]</div>

        </div>

    </div>

</script>