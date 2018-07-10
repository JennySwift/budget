<template>
    <div v-show="filterTab === 'show'" v-slide="showContent" class="section">

        <h4 v-on:click="showContent = !showContent">Invalid Allocations <dropdown-arrow :content-visible="showContent"></dropdown-arrow></h4>

        <div class="content status">

            <div class="radio">
                <label>
                    <input
                        v-model="shared.filter.invalidAllocation"
                        v-on:change="store.runFilter()"
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
                        v-model="shared.filter.invalidAllocation"
                        v-on:change="store.runFilter()"
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
                showContent: false,
                shared: store.state,
                store: store
            };
        },
        components: {},
        methods: {

        },
        props: [
            'filterTab',
        ],
        mounted: function () {

        }
    }
</script>