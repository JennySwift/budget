<script id="accounts-filter-template" type="x-template">

    <div v-slide="showContent" class="section">

        <h4 v-on:click="showContent = !showContent" class="center">accounts</h4>

        <div class="accounts content">

            {{--Show--}}
            <div v-show="filterTab === 'show'">

                <div class="checkbox-container">
                    <input
                        v-model="filter.accounts.in"
                        value="all"
                        type="checkbox"
                        id="accounts-filter-in-all"
                    >
                    <label for="accounts-filter-in-all">all</label>
                </div>

                <div class="checkbox-container">
                    <input
                        v-model="filter.accounts.in"
                        value="none"
                        type="checkbox"
                        id="accounts-filter-in-none"
                    >
                    <label for="accounts-filter-in-none">none</label>
                </div>

                <div v-for="account in accounts" class="checkbox-container">
                    <input
                        type="checkbox"
                        :id="account.name"
                        :value="account"
                        {{--:disabled="filter.accounts.out.indexOf(account.id) !== -1"--}}
                        v-model="filter.accounts.in"
                        v-on:change="runFilter()"
                    >
                    <label
                        :for="account.name"
                        {{--v-bind:class="{'disabled': filter.accounts.out.indexOf(account.id) !== -1}"--}}
                    >
                        @{{account.name}}
                    </label>
                </div>

            </div>

            {{--Hide--}}
            <div v-show="filterTab === 'hide'">

                <div v-for="account in accounts" class="checkbox-container">
                    <input
                            type="checkbox"
                            :id="account.name"
                            :value="account"
                            {{--:disabled="filter.accounts.in.indexOf(account.id) !== -1"--}}
                            v-model="filter.accounts.in"
                            v-on:change="runFilter()"
                    >
                    <label
                            :for="account.name"
                            {{--v-bind:class="{'disabled': filter.accounts.in.indexOf(account.id) !== -1}"--}}
                    >
                        @{{account.name}}
                    </label>
                </div>

            </div>

        </div>

    </div>

</script>
