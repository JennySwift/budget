<template>
    <div v-slide="showContent" class="section">
        <h4 v-on:click="showContent = !showContent">{{label}}<dropdown-arrow :content-visible="showContent"></dropdown-arrow></h4>

        <div class="content">
            <slot name="content"></slot>
            <div
                v-show="filterTab === 'show'"
                class="form-group"
            >
                <slot name="filterIn"></slot>
            </div>

            <div
                v-show="filterTab === 'hide'"
                class="form-group"
            >
                <slot name="filterOut"></slot>
            </div>
        </div>
    </div>
</template>

<script>
    import FilterRepository from '../../repositories/FilterRepository'
    export default {
        data: function () {
            return {
                showContent: false,
                shared: store.state
            };
        },
        components: {},
        methods: {


        },
        props: [
            'filterTab',
            'label'
        ],
        mounted: function () {

        }
    }
</script>