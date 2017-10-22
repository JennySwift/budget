<template>
    <div v-slide="showContent" class="section">

        <h4 v-on:click="showContent = !showContent">Number of Budgets <dropdown-arrow :content-visible="showContent"></dropdown-arrow></h4>

        <div class="content budget">

            <!--Filter in-->

            <div v-show="filterTab === 'show'">

                <!--Filter in all-->
                <div class="radio inline">
                    <label>
                        <input
                            v-model="filter.numBudgets.in"
                            v-on:change="runFilter()"
                            type="radio"
                            name="filter-num-budgets-in"
                            value="all"
                        >
                        Any number of budgets
                    </label>
                </div>

                <!--Filter in transactions with no budgets-->
                <div class="radio">
                    <label>
                        <input
                            v-model="filter.numBudgets.in"
                            v-on:change="runFilter()"
                            type="radio"
                            name="filter-num-budgets-in"
                            value="zero"
                        >
                        No budgets
                    </label>
                </div>

                <!--Filter in transactions with single budgets-->
                <div class="radio">
                    <label>
                        <input
                            v-model="filter.numBudgets.in"
                            v-on:change="runFilter()"
                            type="radio"
                            name="filter-num-budgets-in"
                            value="single"
                        >
                        One budget
                    </label>
                </div>

                <!--Filter in transactions with multiple budgets-->
                <div class="radio">
                    <label>
                        <input
                            v-model="filter.numBudgets.in"
                            v-on:change="runFilter()"
                            type="radio"
                            name="filter-num-budgets-in"
                            value="multiple"
                        >
                        Multiple budgets
                    </label>
                </div>

            </div>

            <!--Filter out-->

            <div v-show="filterTab === 'hide'">

                <!--Filter out none-->
                <div class="radio">
                    <label>
                        <input
                            v-model="filter.numBudgets.out"
                            v-on:change="runFilter()"
                            type="radio"
                            name="filter-num-budgets-out"
                            value="none"
                        >
                        Do not filter out by number of budgets
                    </label>
                </div>

                <!--Filter out transactions with no budgets-->
                <div class="radio">
                    <label>
                        <input
                            v-model="filter.numBudgets.out"
                            v-on:change="runFilter()"
                            type="radio"
                            name="filter-num-budgets-out"
                            value="zero"
                        >
                        No budgets
                    </label>
                </div>

                <!--Filter out transactions with single budgets-->
                <div class="radio">
                    <label>
                        <input
                            v-model="filter.numBudgets.out"
                            v-on:change="runFilter()"
                            type="radio"
                            name="filter-num-budgets-out"
                            value="single"
                        >
                        One budget
                    </label>
                </div>

                <!--Filter out transactions with multiple budgets-->
                <div class="radio">
                    <label>
                        <input
                            v-model="filter.numBudgets.out"
                            v-on:change="runFilter()"
                            type="radio"
                            name="filter-num-budgets-out"
                            value="multiple"
                        >
                        Multiple budgets
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