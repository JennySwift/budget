var ToolbarForFilter = Vue.component('toolbar-for-filter', {
    template: '#toolbar-for-filter-template',
    data: function () {
        return {
            filterRepository: FilterRepository.state
        };
    },
    components: {},
    computed: {
        filter: function () {
          return this.filterRepository.filter;
        }
    },
    methods: {

        /**
         *
         */
        showMassTransactionUpdatePopup: function () {
            $.event.trigger('show-mass-transaction-update-popup');
        },

        /**
         * 
         */
        resetFilter: function () {
            FilterRepository.resetFilter();
            this.runFilter();
        },

        /**
         *
         */
        changeNumToFetch: function () {
            FilterRepository.updateRange(this.filter.numToFetch);
            this.runFilter();
        },

        /**
         * Todo: I might not need some of this code (not allowing offset to be less than 0)
         * since I disabled the button if that is the case
         */
        prevResults: function () {
            FilterRepository.prevResults(this);
        },

        /**
         *
         */
        nextResults: function () {
            FilterRepository.nextResults(this);
        },

        /**
         *
         */
        listen: function () {
            var that = this;
        }
    },
    props: [
        'filterTotals',
        'runFilter'
    ],
    ready: function () {
        this.listen();
    }
});