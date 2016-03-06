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

        updateAllocation: function ($keycode, $type, $value, $budget_id) {
            if ($keycode === 13) {
                $scope.showLoading();
                TransactionsFactory.updateAllocation($type, $value, $scope.allocationPopup.id, $budget_id)
                    .then(function (response) {
                        $scope.allocationPopup.budgets = response.data.budgets;
                        $scope.allocationPopup.totals = response.data.totals;
                        $scope.hideLoading();
                    })
                    .catch(function (response) {
                        $scope.responseError(response);
                    });
            }
        },

        updateAllocationStatus: function () {
            $scope.showLoading();
            TransactionsFactory.updateAllocationStatus($scope.allocationPopup)
                .then(function (response) {
                    $scope.hideLoading();
                })
                .catch(function (response) {
                    $scope.responseError(response);
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
    }
});
