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

    <div class="form-group">
        <button v-on:click="updatePreferences()" class="btn btn-success">Save</button>
    </div>

</div>

</script>