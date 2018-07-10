<template>
    <div class="input-group">
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

        <span class="input-group-btn">
            <clear-button :field="field" :type="type"></clear-button>
        </span>
    </div>

</template>

<script>
    import FilterRepository from '../../repositories/FilterRepository'
    import ClearButton from './ClearButtonComponent'
    export default {
        data: function () {
            return {
                shared: store.state
            }
        },
        components: {
            'clear-button': ClearButton
        },
        methods: {
            runFilter: function () {
                // FilterRepository.resetOffset();
                FilterRepository.runFilter();
            },
            updateValue: function ($event) {
                store.set($event.target.value, this.path)
            },
        },
        props: [
            'id',
            'placeholder',
            'model',
            'path',
            'field',
            'type'
        ]
    }
</script>

<style lang="scss" type="text/scss">
    //@import '../../sass/shared/index';
</style>