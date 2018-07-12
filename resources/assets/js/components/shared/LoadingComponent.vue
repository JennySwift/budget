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

<style lang="scss" type="text/scss">
    @import '../../../sass/shared/index';
    #loading {
        position: fixed;
        top: 0;
        bottom: 0;
        margin: auto;
        color: $info;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 100%;
        height: 60px;
        z-index: $zIndex4;
        i {
            font-size: 40px;
        }
    }

    #page-loading {
        background: white;
        height: 100%;
        width: 100%;
        position: absolute;
        top: 0;
        z-index: 99;
        font-size: 40px;
        color: #5bc0de;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>

