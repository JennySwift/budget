
<div filter-dropdowns-directive
     class="section">

    <h4 class="center">number of budgets</h4>

    <div class="content budget">

        <div v-show="filterTab === 'show'">
            <input
                v-model="filter.numBudgets.in"
                v-on:change="runFilter()"
                type="radio"
                name="numBudgetsIn"
                value="all">
            <label for="">All</label>
        </div>

        <div v-show="filterTab === 'show'">
            <input
                v-model="filter.numBudgets.in"
                v-on:change="runFilter()"
                type="radio"
                name="numBudgetsIn"
                value="zero">
            <label for="">No budgets</label>
        </div>

        <div v-show="filterTab === 'hide'">
            <input
                    v-model="filter.numBudgets.out"
                    v-on:change="runFilter()"
                    type="radio"
                    name="numBudgetsOut"
                    value="none">
            <label for="">None (Do not filter out)</label>
        </div>

        <div v-show="filterTab === 'hide'">
            <input
                    v-model="filter.numBudgets.out"
                    v-on:change="runFilter()"
                    type="radio"
                    name="numBudgetsOut"
                    value="zero">
            <label for="">No budgets</label>
        </div>

        <div v-show="filterTab === 'show'">
            <input
                v-model="filter.numBudgets.in"
                v-on:change="runFilter()"
                type="radio"
                name="numBudgetsIn"
                value="single">
            <label for="">Single</label>
        </div>

        <div v-show="filterTab === 'hide'">
            <input
                    v-model="filter.numBudgets.out"
                    v-on:change="runFilter()"
                    type="radio"
                    name="numBudgetsOut"
                    value="single">
            <label for="">Single</label>
        </div>

        <div v-show="filterTab === 'show'">
            <input
                v-model="filter.numBudgets.in"
                v-on:change="runFilter()"
                type="radio"
                name="numBudgetsIn"
                value="multiple">
            <label for="">Multiple</label>
        </div>

        <div v-show="filterTab === 'hide'">
            <input
                    v-model="filter.numBudgets.out"
                    v-on:change="runFilter()"
                    type="radio"
                    name="numBudgetsOut"
                    value="multiple">
            <label for="">Multiple</label>
        </div>

    </div>

</div>