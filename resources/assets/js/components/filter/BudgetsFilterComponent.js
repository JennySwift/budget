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
        clearBudgetField: function (type1, type2) {
            if (type2) {
                this.filter.budgets[type1][type2] = [];
            }
            else {
                this.filter.budgets[type1] = [];
            }
            this.runFilter();
        }

    },
    props: [
        'filter',
        'filterTab',
        'runFilter',
        'budgets'
    ],
    ready: function () {

    },
    events: {
        'budget-chosen': function () {
            this.runFilter();
        },
        'budget-removed': function () {
            this.runFilter();
        }
    }
});