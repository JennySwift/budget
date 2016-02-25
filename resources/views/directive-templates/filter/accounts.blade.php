<script type="text/v-template" id="filter-accounts-template">

    <div filter-dropdowns-directive
         class="section">

        <h4 class="center">accounts</h4>

        <div class="accounts content">

            <div>
                <input type="checkbox">
                <label for="">all</label>
            </div>

            <div>
                <input type="checkbox">
                <label for="">none</label>
            </div>

            <div v-show="filterTab === 'show'" v-repeat="account in accounts">
                <input
                        checklist-model="filter.accounts.in"
                        checklist-value="account.id"
                        checklist-change="runFilter()"
                        v-disabled="filter.accounts.out.indexOf(account.id) !== -1"
                        type="checkbox">

                <label
                        v-bind:class="{'disabled': filter.accounts.out.indexOf(account.id) !== -1}"
                        for="">
                    [[account.name]]
                </label>
            </div>

            <div v-show="filterTab === 'hide'" v-repeat="account in accounts">
                <input
                        checklist-change="runFilter()"
                        checklist-model="filter.accounts.out"
                        checklist-value="account.id"
                        v-disabled="filter.accounts.in.indexOf(account.id) !== -1"
                        type="checkbox">

                <label
                        v-bind:class="{'disabled': filter.accounts.in.indexOf(account.id) !== -1}"
                        for="">
                    [[account.name]]
                </label>
            </div>

        </div>

    </div>

</script>
