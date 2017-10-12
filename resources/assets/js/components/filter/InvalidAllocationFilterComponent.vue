<template>
    <div v-show="filterTab === 'show'" v-slide="showContent" class="section">

        <h4 v-on:click="showContent = !showContent" class="center">Invalid allocation</h4>

        <div class="content status">

            <div class="radio">
                <label>
                    <input
                        v-model="filter.invalidAllocation"
                        v-on:change="runFilter()"
                        type="radio"
                        name="invalid-allocation"
                        value="false"
                    >
                    Any
                </label>
            </div>

            <div class="radio">
                <label>
                    <input
                        v-model="filter.invalidAllocation"
                        v-on:change="runFilter()"
                        type="radio"
                        name="invalid-allocation"
                        value="true"
                    >
                    Invalid
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