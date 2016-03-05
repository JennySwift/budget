var DatesFilter = Vue.component('dates-filter', {
    template: '#dates-filter-template',
    data: function () {
        return {
            showContent: false
        };
    },
    components: {},
    methods: {
        filterDate: function ($keycode) {
            if ($keycode !== 13) {
                return false;
            }
            $rootScope.$emit('runFilter');
        },

        /**
         * $type is either 'in' or 'out'
         * @param $field
         * @param $type
         */
        clearDateField: function ($field, $type) {
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