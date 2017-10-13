<template>
    <!--<div v-show="loading" id="loading">-->
    <!--<i class="fa fa-spinner fa-pulse"></i>-->
    <!--</div>-->

    <div v-show="loading" id="loading" class="sk-three-bounce">
        <div class="sk-child sk-bounce1"></div>
        <div class="sk-child sk-bounce2"></div>
        <div class="sk-child sk-bounce3"></div>
    </div>
</template>

<script>
//    import store from '../../repositories/Store'
    export default {
        data: function () {
            return {
                shared: store.state
            };
        },
        template: "#loading-template",
        computed: {
            loading: function () {
                return this.shared.loading;
            }
        }
    }
</script>

