var Transactions = Vue.component('transactions', {
    template: '#transactions-template',
    data: function () {
        return {
            me: me,
            accountsRepository: AccountsRepository.state,
            transactionsRepository: TransactionsRepository.state,
            showStatus: false,
            showDate: true,
            showDescription: true,
            showMerchant: true,
            showTotal: true,
            showType: true,
            showAccount: true,
            showDuration: true,
            showReconciled: true,
            showAllocated: true,
            showBudgets: true,
            showDelete: true,
        };
    },
    computed: {
        transactions: function () {
          return this.transactionsRepository.transactions;
        }
    },
    components: {},
    methods: {
        
    },
    props: [
        'transactionPropertiesToShow'
    ],
    ready: function () {

    }
});