var Filter = Vue.component('filter', {
    template: '#filter-template',
    data: function () {
        return {
            filterTab: 'show',
            filter: FilterRepository.filter,
            showFilter: false,
            filterTotals: {}
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
         * This is here, not in the TotalsForFilterComponent, because the ToolbarForFilterComponent also needs the totals
         * Todo: should be GET not POST
         */
        getBasicFilterTotals: function () {
            this.filter = FilterRepository.formatDates(FilterRepository.filter);

            var data = {
                filter: this.filter
            };

            $.event.trigger('show-loading');
            this.$http.post('/api/filter/basicTotals', data, function (response) {
                    this.filterTotals = response;
                    $.event.trigger('hide-loading');
                })
                .error(function (response) {
                    HelpersRepository.handleResponseError(response);
                });
        },

        /**
         *
         */
        listen: function () {
            var that = this;

            $(document).on('toggle-filter', function (event) {
                that.showFilter = !that.showFilter;
            });

            $(document).on('run-filter', function (event) {
                $.event.trigger('get-basic-filter-totals');
                if (that.tab === 'transactions') {
                    $.event.trigger('filter-transactions', [that.filter]);
                }
                else {
                    $.event.trigger('get-graph-totals', [that.filter]);
                }
            });

            $(document).on('reset-filter', function (event) {
                that.filter = FilterRepository.filter;
            });

            $(document).on('get-basic-filter-totals', function (event) {
                that.getBasicFilterTotals();
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
        this.getBasicFilterTotals();
        this.runFilter();
    }
});
