var AllocationPopup = Vue.component('allocation-popup', {
    template: '#allocation-popup-template',
    data: function () {
        return {
            transaction: {},
            allocationTotals: {},
            showPopup: false,
            isNewTransaction: false
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        //updateAllocationStatus: function () {
        //    $.event.trigger('show-loading');
        //
        //    var data = {
        //        allocated: HelpersRepository.convertBooleanToInteger(this.transaction.allocated)
        //    };
        //
        //    this.$http.put('/api/transactions/' + this.transaction.id, data, function (response) {
        //        $.event.trigger('provide-feedback', ['Allocation updated', 'success']);
        //        $.event.trigger('hide-loading');
        //    })
        //    .error(function (response) {
        //        HelpersRepository.handleResponseError(response);
        //    });
        //},

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
        closePopup: function (event) {
            if (event) {
                HelpersRepository.closePopup(event, this);
            }
            else {
                //Close button was clicked
                this.showPopup = false;
            }

            if (this.isNewTransaction) {
                FilterRepository.runFilter(this);
            }
        },

        /**
         *
         */
        listen: function () {
            var that = this;
            $(document).on('show-allocation-popup', function (event, transaction, isNewTransaction) {
                that.transaction = transaction;
                that.isNewTransaction = isNewTransaction;
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
            this.getAllocationTotals();
            this.transaction.budgets = response.budgets;
            this.transaction.validAllocation = response.validAllocation;
        }
    }
});
