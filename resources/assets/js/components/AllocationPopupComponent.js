var AllocationPopup = Vue.component('allocation-popup', {
    template: '#allocation-popup-template',
    data: function () {
        return {
            transaction: {},
            allocationTotals: {},
            showPopup: false
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        updateAllocationStatus: function () {
            $.event.trigger('show-loading');

            var data = {
                allocated: HelpersRepository.convertBooleanToInteger(this.transaction.allocated)
            };

            this.$http.put('/api/transactions/' + this.transaction.id, data, function (response) {
                $.event.trigger('provide-feedback', ['Allocation updated', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },

        /**
        *
        */
        getAllocationTotals: function () {
            $.event.trigger('show-loading');
            this.$http.get('/api/transactions/' + this.transaction.id, function (response) {
                this.allocationTotals = response;
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        },


        /**
         *
         */
        closePopup: function ($event) {
            HelpersRepository.closePopup($event, this);
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-allocation-popup', function (event, transaction) {
                that.transaction = transaction;
                that.showPopup = true;
                that.getAllocationTotals();
            });
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {
        this.listen();
    },
    events: {
        'budget-allocation-updated': function (response) {
            this.transaction.budgets = response.budgets;
            this.allocationTotals = response.totals;
        }
    }
});
