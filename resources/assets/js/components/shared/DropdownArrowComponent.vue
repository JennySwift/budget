<template>
    <span v-bind:class="{'content-visible': contentVisible}" class="fa fa-caret-down dropdown-arrow"></span>

</template>

<script>
    export default {
        data: function () {
            return {

            }
        },
        methods: {

        },
        props: [
            'contentVisible'
        ]
    }
</script>

<style lang="scss" type="text/scss">
    .fa-caret-down {
        /*position: absolute;*/
        /*right: 13px;*/
        /*top: 8px;*/
        font-size: 20px;
        cursor: pointer;
        color: #777;
        transition: transform .5s ease;
        &.content-visible {
             transform: rotate(-180deg);
        }
    }
    .dropdown-arrow {
        position: relative;
        top: 1px;
        margin-left: 4px;
    }
</style>