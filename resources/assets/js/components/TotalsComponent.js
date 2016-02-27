var Totals = Vue.component('totals', {
    template: '#totals-template',
    data: function () {
        return {
            totalChanges: [],
            sideBarTotals: [],
            totalsLoading: false
        };
    },
    components: {},
    filters: {
        /**
         *
         * @param number
         * @param howManyDecimals
         * @returns {Number}
         */
        numberFilter: function (number, howManyDecimals) {
            return HelpersRepository.numberFilter(number, howManyDecimals);
        }
    },
    methods: {
        /**
         * Get all the totals
         * @returns {*}
         */
        //getTotals: function () {
        //    var $url = '/api/totals';
        //
        //    return $http.get($url);
        //},

        /**
        *
        */
        getSideBarTotals: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/totals/sidebar', function (response) {
                this.sideBarTotals = response.data;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        getTotals: function () {
            var $url = '/api/totals/sidebar';

            return $http.get($url);
        },
        getFixedBudgetTotals: function () {
            var $url = '/api/totals/fixedBudget';

            return $http.get($url);
        },
        getFlexBudgetTotals: function () {
            var $url = '/api/totals/flexBudget';

            return $http.get($url);
        },
        getUnassignedBudgetTotals: function () {
            var $url = '/api/totals/unassignedBudget';

            return $http.get($url);
        }
    },
    props: [
        'show'
    ],
    ready: function () {
        this.getSideBarTotals();
    }
});