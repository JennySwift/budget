var TotalsFilter = Vue.component('totals-filter', {
    template: '#totals-filter-template',
    data: function () {
        return {

        };
    },
    components: {},
    methods: {
        filterTotal: function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            $rootScope.$emit('runFilter');
        },

        /**
         * $type is either 'in' or 'out'
         *
         * @DO:
         * This method is duplicated in other parts of the filter, but
         * for some reason when I had it in my FilterController, both
         * parameters were undefined.
         *
         * @param $field
         * @param $type
         */
        clearFilterField: function ($field, $type) {
            $scope.filter[$field][$type] = "";
            $rootScope.$emit('runFilter');
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