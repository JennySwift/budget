<script id="reconciled-filter-template" type="x-template">

    <div v-show="filterTab === 'show'" v-slide="showContent" class="section">

        <h4 v-on:click="showContent = !showContent" class="center">reconciled</h4>

        <div class="content status">

            <div class="radio">
                <label>
                    <input
                        v-model="filter.reconciled"
                        v-on:change="runFilter()"
                        type="radio"
                        name="status"
                        value="any"
                    >
                        Any
                </label>
            </div>

            <div class="radio">
                <label>
                    <input
                        v-model="filter.reconciled"
                        v-on:change="runFilter()"
                        type="radio"
                        name="status"
                        value="true"
                    >
                        Reconciled
                </label>
            </div>

            <div class="radio">
                <label>
                    <input
                        v-model="filter.reconciled"
                        v-on:change="runFilter()"
                        type="radio"
                        name="status"
                        value="false"
                    >
                        Unreconciled
                </label>
            </div>

        </div>

    </div>

</script>