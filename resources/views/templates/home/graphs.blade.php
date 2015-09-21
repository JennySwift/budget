<div id="graphs">

    <div ng-repeat="month in graphFigures.months" class="month-container">

        <div id="months">
            <div
                ng-style="{height: month.expensesHeight + 'px'}"
                id="debit">

                <div class="span-container">
                    <span ng-if="month.expenses < 0" class="badge">[[month.expenses | number:2]]</span>
                </div>

            </div>

            <div
                ng-style="{height: month.incomeHeight + 'px'}"
                id="credit">

                <div class="span-container">
                    <span ng-if="month.income > 0" class="badge">[[month.income | number:2]]</span>
                </div>

            </div>
        </div>

        <div>[[month.month]]</div>

    </div>

</div>