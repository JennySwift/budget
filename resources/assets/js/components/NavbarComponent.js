var Navbar = Vue.component('navbar', {
    template: '#navbar-template',
    data: function () {
        return {
            me: me,
            page: 'home',
        };
    },
    components: {},
    methods: {
        toggleFilter: function () {
            $.event.trigger('toggle-filter');
        },

        /**
         *
         */
        showAllTransactionProperties: function () {
            this.transactionPropertiesToShow = ShowRepository.setTransactionDefaults();
        },

        /**
         *
         * @param property
         */
        toggleTransactionProperty: function (property) {
            this.transactionPropertiesToShow[property] = !this.transactionPropertiesToShow[property];
            this.transactionPropertiesToShow.all = this.calculateIfAllTransactionPropertiesAreShown();
        },

        /**
         *
         * @returns {*}
         */
        calculateIfAllTransactionPropertiesAreShown: function () {
            var that = this;
            var allShown = true;
            $.each(this.transactionPropertiesToShow, function (key, value) {
                if (key !== 'all' && !value) {
                    allShown = false;
                }
            });

            return allShown;

            //var hiddenProperties = _.filter(that.transactionPropertiesToShow, function (property) {
            //    return property == false;
            //});
            //
            //if (hiddenProperties.length > 0) {
            //    return false;
            //}
            //
            //return true;
        }
    },
    props: [
        'show',
        'transactionPropertiesToShow'
    ],
    ready: function () {

    }
});
