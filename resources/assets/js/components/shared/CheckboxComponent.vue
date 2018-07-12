<template>
    <div>
        <input
            v-model="model"
            type="checkbox"
            :id="id + '-checkbox'">

        <label :for="id + '-checkbox'">
            <div class="label-icon animated">
                <i class="fa fa-check"></i>
            </div>
        </label>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                //icon: $(elem).find('.label-icon'),
                icon: ''
            };
        },
        components: {},
        methods: {
            toggleIcon: function () {
                if (!this.model) {
                    //Input was checked and now it won't be
                    this.hideIcon();
                }
                else {
                    //Input was not checked and now it will be
                    this.showIcon();
                }
            },

            hideIcon: function () {
                $(this.icon).removeClass(this.animateIn)
                    .addClass(this.animateOut);
            },

            showIcon: function () {
                $(this.icon).css('display', 'flex')
                    .removeClass(this.animateOut)
                    .addClass(this.animateIn);
            },

//            oldCode: function () {
//                //Make the checkbox checked on page load if it should be
//                if (this.model === true) {
//                    this.showIcon();
//                }
//
//                this.$watch('model', function (newValue, oldValue) {
//                    this.toggleIcon();
//                });
//            }

        },
        props: [
            'model',
            'id',
            'animateIn',
            'animateOut'
        ],
        mounted: function () {

        }
    }
</script>
