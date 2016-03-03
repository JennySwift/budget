var TotalsForFilter = Vue.component('totals-for-filter', {
    template: '#totals-for-filter-template',
    data: function () {
        return {
            basicFilterTotals: {}
        };
    },
    components: {},
    methods: {

        /**
        * Todo: should be GET not POST
        */
        getBasicFilterTotals: function () {
            $.event.trigger('show-loading');
            this.$http.post('/api/filter/basicTotals', this.filter, function (response) {
                this.basicFilterTotals = response;
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
            $(document).on('get-basic-filter-totals', function (event) {
                that.getBasicFilterTotals();
            });
        }
    },
    props: [
        'show',
        'filter'
    ],
    ready: function () {
        this.listen();
    }
});