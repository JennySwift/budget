var Checkbox = Vue.component('checkbox', {
    template: '#checkbox-template',
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

    },
    props: [
        'model',
        'id',
        'animateIn',
        'animateOut'
    ],
    ready: function () {

    }
});

////Make the checkbox checked on page load if it should be
//if (this.model === true) {
//    this.showIcon();
//}
//
//this.$watch('model', function (newValue, oldValue) {
//    this.toggleIcon();
//});
