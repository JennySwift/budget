<template>
    <div v-show="filterTab === 'show'" v-slide="showContent" class="section">

        <h4 v-on:click="showContent = !showContent">Reconciled <dropdown-arrow :content-visible="showContent"></dropdown-arrow></h4>

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
</template>

<script>
    export default {
        data: function () {
            return {
                showContent: false
            };
        },
        components: {},
        methods: {

        },
        props: [
            'filter',
            'filterTab',
            'runFilter'
        ],
        mounted: function () {

        }
    }
</script>