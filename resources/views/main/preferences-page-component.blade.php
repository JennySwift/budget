<script id="preferences-page-template" type="x-template">

<div id="preferences-page">

    <h1>Preferences</h1>

    @include('main.preferences.colors')

    <h3>New Transaction</h3>

    <div class="new-transaction">

        <div class="checkbox-container">
            <input
                v-model="me.preferences.clearFields"
                id="preferences-clear-fields"
                type="checkbox"
            >
            <label for="preferences-clear-fields">Clear fields upon entering new transaction</label>
        </div>

        <div class="checkbox-container">
            <input
                v-model="me.preferences.autocompleteDescription"
                id="preferences-autocomplete-description"
                type="checkbox"
            >
            <label for="preferences-autocomplete-description">Use description field to autocomplete new transaction</label>
        </div>

        <div class="checkbox-container">
            <input
                    v-model="me.preferences.autocompleteMerchant"
                    id="preferences-autocomplete-merchant"
                    type="checkbox"
            >
            <label for="preferences-autocomplete-merchant">Use merchant field to autocomplete new transaction</label>
        </div>
    </div>

    <h3>Date Format</h3>

    <div class="formats">
        <div class="form-group">

            <div>
                <label>DD/MM/YY</label>
                <input v-model="me.preferences.dateFormat" type="radio" value="DD/MM/YY">
            </div>

            <div>
                <label>DD/MM/YYYY</label>
                <input v-model="me.preferences.dateFormat" type="radio" value="DD/MM/YYYY">
            </div>

        </div>
    </div>

    <h3>Totals to Show</h3>

    <div class="totals-to-show">
        <div class="form-group">

            <div class="checkbox-container">
                <input
                        v-model="me.preferences.show.totals.credit"
                        id="preferences-show-credit"
                        type="checkbox"
                >
                <label for="preferences-show-credit">Credit</label>
            </div>

            <div class="checkbox-container">
                <input
                        v-model="me.preferences.show.totals.remainingFixedBudget"
                        id="preferences-show-remainingFixedBudget"
                        type="checkbox"
                >
                <label for="preferences-show-remaining-fixed-budget">Remaining Fixed Budget</label>
            </div>

            <div class="checkbox-container">
                <input
                        v-model="me.preferences.show.totals.expensesWithoutBudget"
                        id="preferences-show-expenses-without-budget"
                        type="checkbox"
                >
                <label for="preferences-show-expenses-without-budget">Expenses without a fixed or flex budget</label>
            </div>

            <div class="checkbox-container">
                <input
                        v-model="me.preferences.show.totals.expensesWithFixedBudgetBeforeStartingDate"
                        id="preferences-show-expenses-with-fixed-budget-before-starting-date"
                        type="checkbox"
                >
                <label for="preferences-show-expenses-with-fixed-budget-before-starting-date">Expenses with fixed budget before starting date</label>
            </div>

            <div class="checkbox-container">
                <input
                        v-model="me.preferences.show.totals.expensesWithFixedBudgetAfterStartingDate"
                        id="preferences-show-expenses-with-fixed-budget-after-starting-date"
                        type="checkbox"
                >
                <label for="preferences-show-expenses-with-fixed-budget-after-starting-date">Expenses with fixed budget after starting date</label>
            </div>

            <div class="checkbox-container">
                <input
                        v-model="me.preferences.show.totals.expensesWithFlexBudgetBeforeStartingDate"
                        id="preferences-show-expenses-with-flex-budget-before-starting-date"
                        type="checkbox"
                >
                <label for="preferences-show-expenses-with-flex-budget-before-starting-date">Expenses with flex budget before starting date</label>
            </div>

            <div class="checkbox-container">
                <input
                        v-model="me.preferences.show.totals.expensesWithFlexBudgetAfterStartingDate"
                        id="preferences-show-expenses-with-flex-budget-after-starting-date"
                        type="checkbox"
                >
                <label for="preferences-show-expenses-with-flex-budget-after-starting-date">Expenses with flex budget after starting date</label>
            </div>

            <div class="checkbox-container">
                <input
                        v-model="me.preferences.show.totals.savings"
                        id="preferences-show-savings"
                        type="checkbox"
                >
                <label for="preferences-show-savings">Savings</label>
            </div>

            <div class="checkbox-container">
                <input
                        v-model="me.preferences.show.totals.remainingBalance"
                        id="preferences-show-remaining-balance"
                        type="checkbox"
                >
                <label for="preferences-show-remaining-balance">Remaining balance</label>
            </div>

            <div class="checkbox-container">
                <input
                        v-model="me.preferences.show.totals.debit"
                        id="preferences-show-debit"
                        type="checkbox"
                >
                <label for="preferences-show-debit">Debit</label>
            </div>

            <div class="checkbox-container">
                <input
                        v-model="me.preferences.show.totals.balance"
                        id="preferences-show-balance"
                        type="checkbox"
                >
                <label for="preferences-show-balance">Balance</label>
            </div>

            <div class="checkbox-container">
                <input
                        v-model="me.preferences.show.totals.reconciled"
                        id="preferences-show-reconciled"
                        type="checkbox"
                >
                <label for="preferences-show-reconciled">Reconciled</label>
            </div>

            <div class="checkbox-container">
                <input
                        v-model="me.preferences.show.totals.cumulativeFixedBudget"
                        id="preferences-show-cumulative-fixed-budget"
                        type="checkbox"
                >
                <label for="preferences-show-cumulative-fixed-budget">Cumulative fixed budget</label>
            </div>

        </div>
    </div>

    <div class="form-group">
        <button v-on:click="updatePreferences()" class="btn btn-success">Save</button>
    </div>

</div>

</script>