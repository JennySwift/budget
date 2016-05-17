var Filter = Vue.component('filter', {
    template: '#filter-template',
    data: function () {
        return {
            filterTab: 'show',
            filterRepository: FilterRepository.state,
            showFilter: false,
        };
    },
    components: {},
    computed: {
        filter: function () {
          return this.filterRepository.filter;
        },
        filterTotals: function () {
            return this.filterRepository.filterTotals;
        }
    },
    methods: {

        /**
         * 
         */
        runFilter: function () {
            FilterRepository.runFilter(this);
        },

        /**
         *
         * @param field
         * @param type - either 'in' or 'out'
         */
        clearFilterField: function (field, type) {
            FilterRepository.clearFilterField(this, field, type);
        },

        /**
         *
         */
        listen: function () {
            var that = this;

            $(document).on('toggle-filter', function (event) {
                that.showFilter = !that.showFilter;
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
        this.runFilter();
    }
});
