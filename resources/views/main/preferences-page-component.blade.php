<script id="preferences-page-template" type="x-template">

<div id="preferences-page">

    <h1>Preferences</h1>

    @include('main.preferences.colors')

    <div class="form-group clear-fields-container">
        <label for="clear-fields">Clear fields upon entering new transaction</label>

        <input v-model="me.preferences.clearFields" type="checkbox" id="clear-fields">
    </div>

    <div class="form-group">
        <div>Date format</div>

        <label>dd/mm/yy</label>
        <input v-model="me.preferences.dateFormat" type="radio" value="dd/mm/yy">

        <label>dd/mm/yyyy</label>
        <input v-model="me.preferences.dateFormat" type="radio" value="dd/mm/yyyy">
    </div>

    <div class="form-group">
        <button v-on:click="updatePreferences()" class="btn btn-success">Save</button>
    </div>

</div>

</script>