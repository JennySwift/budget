

<div filter-dropdowns-directive
     v-show="filterTab === 'show'"
     class="section">

    <h4 class="center">reconciled</h4>

    <div class="content status">

        <div>
            <input
                v-model="filter.reconciled"
                v-on:change="runFilter()"
                type="radio"
                name="status"
                value="any">
            <label for="">Any</label>
        </div>

        <div>
            <input
                v-model="filter.reconciled"
                v-on:change="runFilter()"
                type="radio"
                name="status"
                value="true">
            <label for="">Reconciled</label>
        </div>

        <div>
            <input
                v-model="filter.reconciled"
                v-on:change="runFilter()"
                type="radio"
                name="status"
                value="false">
            <label for="">Unreconciled</label>
        </div>

    </div>

</div>
