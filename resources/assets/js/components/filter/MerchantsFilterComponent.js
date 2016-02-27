var MerchantsFilter = Vue.component('merchants-filter', {
    template: '#merchants-filter-template',
    data: function () {
        return {

        };
    },
    components: {},
    methods: {
        filterDescriptionOrMerchant: function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            $scope.resetOffset();
            $rootScope.$emit('runFilter');
        },

        resetOffset: function () {
            $scope.filter.offset = 0;
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
