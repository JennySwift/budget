
<div filter-dropdowns-directive
     class="section">

    <h4 class="center">Budget</h4>

    <div class="content budget">

        <div ng-show="filterTab === 'show'">
            <input
                ng-model="filter.budget.in"
                ng-change="filterTransactions()"
                type="radio"
                name="budgetIn"
                value="all">
            <label for="">All</label>
        </div>

        <div ng-show="filterTab === 'show'">
            <input
                ng-model="filter.budget.in"
                ng-change="filterTransactions()"
                type="radio"
                name="budgetIn"
                value="zero">
            <label for="">No budgets</label>
        </div>

        <div ng-show="filterTab === 'hide'">
            <input
                    ng-model="filter.budget.out"
                    ng-change="filterTransactions()"
                    type="radio"
                    name="budgetOut"
                    value="none">
            <label for="">None (Do not filter out)</label>
        </div>

        <div ng-show="filterTab === 'hide'">
            <input
                    ng-model="filter.budget.out"
                    ng-change="filterTransactions()"
                    type="radio"
                    name="budgetOut"
                    value="zero">
            <label for="">No budgets</label>
        </div>

        <div ng-show="filterTab === 'show'">
            <input
                ng-model="filter.budget.in"
                ng-change="filterTransactions()"
                type="radio"
                name="budgetIn"
                value="single">
            <label for="">Single</label>
        </div>

        <div ng-show="filterTab === 'hide'">
            <input
                    ng-model="filter.budget.out"
                    ng-change="filterTransactions()"
                    type="radio"
                    name="budgetOut"
                    value="single">
            <label for="">Single</label>
        </div>

        <div ng-show="filterTab === 'show'">
            <input
                ng-model="filter.budget.in"
                ng-change="filterTransactions()"
                type="radio"
                name="budgetIn"
                value="multiple">
            <label for="">Multiple</label>
        </div>

        <div ng-show="filterTab === 'hide'">
            <input
                    ng-model="filter.budget.out"
                    ng-change="filterTransactions()"
                    type="radio"
                    name="budgetOut"
                    value="multiple">
            <label for="">Multiple</label>
        </div>

    </div>

</div>