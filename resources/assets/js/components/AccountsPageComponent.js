var AccountsPage = Vue.component('accounts-page', {
    template: '#accounts-page-template',
    data: function () {
        return {
            accountsRepository: AccountsRepository.state,
        };
    },
    components: {},
    filters: {
        /**
         *
         * @param number
         * @param howManyDecimals
         * @returns {Number}
         */
        numberFilter: function (number, howManyDecimals) {
            return HelpersRepository.numberFilter(number, howManyDecimals);
        },
    },
    methods: {

        /**
         *
         * @param account
         */
        showEditAccountPopup: function (account) {
            $.event.trigger('show-edit-account-popup', [account]);
        },

        /**
         *
         * @param account
         */
        viewTransactionsForAccount: function (account) {
            var currentNumToFetch = FilterRepository.state.filter.numToFetch;
            var filter = FilterRepository.resetFilter();
            filter.accounts.in.push(account.id);
            //Show the same number of transactions as the filter was previously set to
            filter.numToFetch = currentNumToFetch;
            filter.displayTo = currentNumToFetch;
            FilterRepository.setFilter(filter);
            router.go('/');
        }
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {

    }
});