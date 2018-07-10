<template>
    <input
        :value="model" @input="updateValue"
        v-on:keyup.13="runFilter()"
        type="text"
        :id="id"
        :name="id"
        :placeholder="placeholder"
        class="form-control"
        formatted-date
    >
</template>

<script>
    import FilterRepository from '../../repositories/FilterRepository'
    export default {
        data: function () {
            return {
                shared: store.state
            }
        },
        methods: {
            runFilter: function () {
                FilterRepository.runFilter();
            },
            updateValue: function ($event) {
                store.set($event.target.value, this.path)
            }
        },
        props: [
            'id',
            'placeholder',
            'model',
            'path'
        ]
    }
</script>

<style lang="scss" type="text/scss">
    //@import '../../sass/shared/index';
</style>