var BudgetsFilter = Vue.component('budgets-filter', {
    template: '#budgets-filter-template',
    data: function () {
        return {
            showContent: false
        };
    },
    components: {},
    methods: {
        /**
         * type1 is 'in' or 'out'.
         * type2 is 'and' or 'or'.
         * @param type1
         * @param type2
         */
        clearTagField: function (type1, type2) {
            if (type2) {
                this.filter.budgets[type1][type2] = [];
            }
            else {
                this.filter.budgets[type1] = [];
            }
        }

    },
    props: [
        'filter',
        'filterTab',
        'runFilter',
        'budgets'
    ],
    ready: function () {

    }
});


//$scope.$watchCollection('filter.budgets.in.and', function (newValue, oldValue) {
//    if (newValue === oldValue) {
//        return;
//    }
//    $rootScope.$emit('runFilter');
//});
//
//$scope.$watchCollection('filter.budgets.in.or', function (newValue, oldValue) {
//    if (newValue === oldValue) {
//        return;
//    }
//    $rootScope.$emit('runFilter');
//});
//
//$scope.$watchCollection('filter.budgets.out', function (newValue, oldValue) {
//    if (newValue === oldValue) {
//        return;
//    }
//    $rootScope.$emit('runFilter');
//});