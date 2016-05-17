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
    },
    props: [
        //data to be received from parent
    ],
    ready: function () {

    }
});