<template>
    <button
        v-on:click="clearFilterField()"
        class="clear-search-button btn btn-default"
    >
            Clear
    </button>
</template>

<script>
    import FilterRepository from '../../repositories/FilterRepository'
    export default {
        methods: {
            /**
             *
             * @param field
             * @param type - either 'in' or 'out'
             */
            clearFilterField: function () {
                FilterRepository.clearFilterField(this.field, this.type);
            },
        },
        props: [
            'field',
            'type'
        ]
    }
</script>

<style lang="scss" type="text/scss">
    //@import '../../sass/shared/index';
</style>