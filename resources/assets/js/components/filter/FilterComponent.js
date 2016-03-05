var Filter = Vue.component('filter', {
    template: '#filter-template',
    data: function () {
        return {
            filterTab: 'show',
            filter: FilterRepository.filter,
            showFilter: false
        };
    },
    components: {},
    methods: {

        /**
         * 
         */
        runFilter: function () {
            $.event.trigger('run-filter');
        },

        /**
         *
         * @param field
         * @param type - either 'in' or 'out'
         */
        clearFilterField: function (field, type) {
            this.filter[field][type] = "";
            this.runFilter();
        },

        /**
         *
         */
        listen: function () {
            var that = this;

            $(document).on('toggle-filter', function (event) {
                that.showFilter = !that.showFilter;
            });

            $(document).on('run-filter', function (event, data) {
                $.event.trigger('get-basic-filter-totals');
                if (that.tab === 'transactions') {
                    $.event.trigger('filter-transactions', [that.filter]);
                }
                else {
                    $.event.trigger('get-graph-totals');
                }
            });

            $(document).on('reset-filter', function (event) {
                that.filter = FilterRepository.filter;
            });
        }
    },
    props: [
        'show',
        'budgets',
        'tab'
    ],
    ready: function () {
        this.listen();
    }
});
