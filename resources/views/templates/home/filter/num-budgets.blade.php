
<div filter-dropdowns-directive
     class="section">

    <h4 class="center">number of budgets</h4>

    <div class="content budget">

        <div ng-show="filterTab === 'show'">
            <input
                ng-model="filter.numBudgets.in"
                ng-change="filterTransactions()"
                type="radio"
                name="numBudgetsIn"
                value="all">
            <label for="">All</label>
        </div>

        <div ng-show="filterTab === 'show'">
            <input
                ng-model="filter.numBudgets.in"
                ng-change="filterTransactions()"
                type="radio"
                name="numBudgetsIn"
                value="zero">
            <label for="">No budgets</label>
        </div>

        <div ng-show="filterTab === 'hide'">
            <input
                    ng-model="filter.numBudgets.out"
                    ng-change="filterTransactions()"
                    type="radio"
                    name="numBudgetsOut"
                    value="none">
            <label for="">None (Do not filter out)</label>
        </div>

        <div ng-show="filterTab === 'hide'">
            <input
                    ng-model="filter.numBudgets.out"
                    ng-change="filterTransactions()"
                    type="radio"
                    name="numBudgetsOut"
                    value="zero">
            <label for="">No budgets</label>
        </div>

        <div ng-show="filterTab === 'show'">
            <input
                ng-model="filter.numBudgets.in"
                ng-change="filterTransactions()"
                type="radio"
                name="numBudgetsIn"
                value="single">
            <label for="">Single</label>
        </div>

        <div ng-show="filterTab === 'hide'">
            <input
                    ng-model="filter.numBudgets.out"
                    ng-change="filterTransactions()"
                    type="radio"
                    name="numBudgetsOut"
                    value="single">
            <label for="">Single</label>
        </div>

        <div ng-show="filterTab === 'show'">
            <input
                ng-model="filter.numBudgets.in"
                ng-change="filterTransactions()"
                type="radio"
                name="numBudgetsIn"
                value="multiple">
            <label for="">Multiple</label>
        </div>

        <div ng-show="filterTab === 'hide'">
            <input
                    ng-model="filter.numBudgets.out"
                    ng-change="filterTransactions()"
                    type="radio"
                    name="numBudgetsOut"
                    value="multiple">
            <label for="">Multiple</label>
        </div>

    </div>

</div>