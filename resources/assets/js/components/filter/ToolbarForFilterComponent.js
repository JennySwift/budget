var ToolbarForFilter = Vue.component('toolbar-for-filter', {
    template: '#toolbar-for-filter-template',
    data: function () {
        return {
            filter: FilterRepository.filter
        };
    },
    components: {},
    methods: {

        /**
         * 
         */
        resetFilter: function () {
            FilterRepository.resetFilter();
            this.filter = FilterRepository.filter;
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
            //make it so the offset cannot be less than 0.
            if (this.filter.offset - this.filter.numToFetch < 0) {
                this.filter.offset = 0;
            }
            else {
                this.filter.offset-= (this.filter.numToFetch * 1);
                FilterRepository.updateRange();
                this.runFilter();
            }
        },

        /**
         *
         */
        nextResults: function () {
            if (this.filter.offset + (this.filter.numToFetch * 1) > this.filterTotals.numTransactions) {
                //stop it going past the end.
                return;
            }

            this.filter.offset+= (this.filter.numToFetch * 1);
            this.updateRange();
            this.runFilter();
        },

        /**
         * Updates filter.displayFrom and filter.displayTo values
         */
        updateRange: function (numToFetch) {
            if (numToFetch) {
                this.filter.numToFetch = numToFetch;
            }

            this.filter.displayFrom = this.filter.offset + 1;
            this.filter.displayTo = this.filter.offset + (this.filter.numToFetch * 1);
        },


        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('set-filter-in-toolbar', function (event) {
                that.filter = FilterRepository.filter;
            });
        }
    },
    props: [
        'filterTotals'
    ],
    ready: function () {
        this.listen();
    }
});

//
//this.$watch('filterFactory.filterBasicTotals', function (newValue, oldValue, scope) {
//    this.filterTotals = newValue;
//});