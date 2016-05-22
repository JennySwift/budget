var FavouriteTransactionsPage = Vue.component('favourite-transactions', {
    template: '#favourite-transactions-page-template',
    data: function () {
        return {
            favouriteTransactions: [],
            accountsRepository: AccountsRepository.state,
            budgetsRepository: BudgetsRepository.state,
            favouriteTransactionsRepository: FavouriteTransactionsRepository.state,
            newFavourite: {
                budgets: []
            },
        };
    },
    components: {},
    computed: {
        budgets: function () {
          return this.budgetsRepository.budgets;
        },
        favouriteTransactions: function () {
            return this.favouriteTransactionsRepository.favouriteTransactions;
        }
    },
    methods: {

        /**
         *
         * @param favouriteTransaction
         */
        showUpdateFavouriteTransactionPopup: function (favouriteTransaction) {
            $.event.trigger('show-update-favourite-transaction-popup', [favouriteTransaction]);
        },

    },
    props: [
        //data to be received from parent
    ],
    ready: function () {

    }
});