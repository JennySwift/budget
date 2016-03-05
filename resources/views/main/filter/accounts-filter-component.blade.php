<script id="accounts-filter-template" type="x-template">

    <div v-slide="showContent" class="section">

        <h4 v-on:click="showContent = !showContent" class="center">accounts</h4>

        <div class="accounts content">

            <div>
                <input type="checkbox">
                <label for="">all</label>
            </div>

            <div>
                <input type="checkbox">
                <label for="">none</label>
            </div>

            <div v-show="filterTab === 'show'" v-for="account in accounts">
                <input
                        checklist-model="filter.accounts.in"
                        checklist-value="account.id"
                        checklist-change="runFilter()"
                        :disabled="filter.accounts.out.indexOf(account.id) !== -1"
                        type="checkbox">

                <label
                        v-bind:class="{'disabled': filter.accounts.out.indexOf(account.id) !== -1}"
                        for="">
                    @{{ account.name }}
                </label>
            </div>

            <div v-show="filterTab === 'hide'" v-for="account in accounts">
                <input
                        checklist-change="runFilter()"
                        checklist-model="filter.accounts.out"
                        checklist-value="account.id"
                        :disabled="filter.accounts.in.indexOf(account.id) !== -1"
                        type="checkbox">

                <label
                        v-bind:class="{'disabled': filter.accounts.in.indexOf(account.id) !== -1}"
                        for="">
                    @{{ account.name }}
                </label>
            </div>

        </div>

    </div>

</script>
