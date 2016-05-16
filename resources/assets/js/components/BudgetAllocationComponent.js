var BudgetAllocation = Vue.component('budget-allocation', {
    template: '#budget-allocation-template',
    data: function () {
        return {
            editingAllocatedFixed: false,
            editingAllocatedPercent: false,
            allocatedFixed: '',
            allocatedPercent: ''
        };
    },
    components: {},
    methods: {

        /**
        *
        */
        updateAllocation: function (type, value) {
            $.event.trigger('show-loading');

            var data = {
                type: type,
                value: value
            };

            this.$http.put('/api/budgets/' + this.budget.id + '/transactions/' + this.transaction.id, data, function (response) {
                this.$dispatch('budget-allocation-updated', response);
                $.event.trigger('provide-feedback', ['Allocation updated', 'success']);
                $.event.trigger('hide-loading');
            })
            .error(function (response) {
                HelpersRepository.handleResponseError(response);
            });
        }


    },
    props: [
        'budget',
        'transaction'
    ],
    ready: function () {

    }
});
