<template>
    <div v-show="showLoading" id="loading">
        <i class="fa fa-spinner fa-pulse"></i>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                showLoading: false
            };
        },
        template: "#loading-template",
        props: [
            //'showLoading'
        ],
        methods: {
            listen: function () {
                var that = this;
                $(document).on('show-loading', function (event, message, type) {
                    that.showLoading = true;
                });
                $(document).on('hide-loading', function (event, message, type) {
                    that.showLoading = false;
                });
            }
        },
        mounted: function () {
            this.listen();
        }
    }
</script>