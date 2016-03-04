<script id="preferences-page-template" type="x-template">

<div id="preferences-page">

    <h1>Preferences</h1>
    
    <pre>@{{$data.me.preferences.colors | json}}</pre>

    <div class="form-group clear-fields-container">
        <label for="clear-fields">Clear fields upon entering new transaction</label>

        <input v-model="me.preferences.clearFields" type="checkbox" id="clear-fields">
    </div>

    @include('main.preferences.colors')


    <div>
        <div>Date format</div>
        <label>dd/mm/yy</label>
        <input v-model="preferences.dateFormat" type="radio" value="dd/mm/yy">
        <label>dd/mm/yyyy</label>
        <input v-model="preferences.dateFormat" type="radio" value="dd/mm/yyyy">
    </div>

    <div>
        <button v-on:click="updatePreferences()" class="btn btn-success">Save</button>
    </div>

</div>

</script>