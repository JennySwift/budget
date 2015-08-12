<div id="graphs">

    <div ng-repeat="month in graphFigures.months" class="month-container">

        <div id="months">
            <div
                ng-style="{height: month.expensesHeight + 'px'}"
                id="debit">
                <span class="badge">[[month.expenses]]</span>
            </div>

            <div
                ng-style="{height: month.incomeHeight + 'px'}"
                id="credit">
                <span class="badge">[[month.income]]</span>
            </div>
        </div>

        <div>[[month.month]]</div>

    </div>

</div>