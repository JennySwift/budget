var AccountsFilter = Vue.component('accounts-filter', {
    template: '#accounts-filter-template',
    data: function () {
        return {
            accounts: [],
            contentVisible: false
        };
    },
    components: {},
    methods: {

        toggleContent: function () {
            if (this.contentVisible) {
                this.hideContent();
            }
            else {
                this.showContent();
            }
        },

        showContent: function () {
            $(this.$el).find('.content').slideDown();
            this.contentVisible = true;
        },

        hideContent: function () {
            $(this.$el).find('.content').slideUp();
            this.contentVisible = false;
        },


    },
    props: [
        'filter',
        'filterTab',
        'runFilter'
    ],
    ready: function () {

    }
});