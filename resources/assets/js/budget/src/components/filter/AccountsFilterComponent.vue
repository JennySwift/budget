<template>
    <div v-slide="showContent" class="section">

        <h4 v-on:click="toggleContent()" class="center">accounts</h4>

        <div class="accounts content">

            <!--Show-->
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

                <div v-for="account in shared.accounts" class="checkbox-container">
                    <input
                        type="checkbox"
                        :id="account.name"
                        :value="account.id"
                        :disabled="filter.accounts.out.indexOf(account.id) !== -1"
                        v-model="filter.accounts.in"
                        v-on:change="runFilter()"
                    >
                    <label
                        :for="account.name"
                        v-bind:class="{'disabled': filter.accounts.out.indexOf(account.id) !== -1}"
                    >
                        {{account.name}}
                    </label>
                </div>

            </div>

            <!--Hide-->
            <div v-show="filterTab === 'hide'">

                <div v-for="account in shared.accounts" class="checkbox-container">
                    <input
                        type="checkbox"
                        :id="account.name"
                        :value="account.id"
                        :disabled="filter.accounts.in.indexOf(account.id) !== -1"
                        v-model="filter.accounts.out"
                        v-on:change="runFilter()"
                    >
                    <label
                        :for="account.name"
                        v-bind:class="{'disabled': filter.accounts.in.indexOf(account.id) !== -1}"
                    >
                        {{account.name}}
                    </label>
                </div>

            </div>

        </div>

    </div>

</template>

<script>
    export default {
        data: function () {
            return {
                showContent: false,
                shared: store.state
            };
        },
        components: {},
        methods: {
            toggleContent: function () {
                console.log('clicked');
                this.showContent = !this.showContent;
            }
        },
        props: [
            'filter',
            'filterTab',
            'runFilter',
        ],
        mounted: function () {

        }
    }
</script>