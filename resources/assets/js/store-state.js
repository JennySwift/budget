import filterDefaults from "./filterDefaults";

export default {
    me: {
        gravatar: '',
        preferences: {
            show: {
                totals: []
            },
            colors: {}
        },
    },
    totalsLoading: false,
    autocompleteLoading: false,
    notifications: [
        // {message: 'Hello', type: 'success'}
    ],
    sideBarTotals: {
        remainingBalance: '',
        remainingFixedBudget: '',
        cumulativeFixedBudget: '',
        credit: '',
        debit: '',
        balance: '',
        reconciledSum: '',
        expensesWithoutBudget: '',
        savings: '',
        expensesWithFixedBudgetBeforeStartingDate: '',
        expensesWithFixedBudgetAfterStartingDate: '',
        expensesWithFlexBudgetBeforeStartingDate: '',
        expensesWithFlexBudgetAfterStartingDate: ''
    },
    totalChanges: {
        remainingBalance: 0,
        remainingFixedBudget: 0,
        cumulativeFixedBudget: 0,
        credit: 0,
        debit: 0,
        balance: 0,
        reconciledSum: 0,
        expensesWithoutBudget: 0,
        savings: 0,
        expensesWithFixedBudgetBeforeStartingDate: 0,
        expensesWithFixedBudgetAfterStartingDate: 0,
        expensesWithFlexBudgetBeforeStartingDate: 0,
        expensesWithFlexBudgetAfterStartingDate: 0,
    },
    env: '',
    //For home page tabs
    tab: '',
    loading: false,
    filter: filterDefaults,
    filterTotals: {},
    allocationTotals: {},
    accounts: [],
    accountsWithBalances: [],
    budgets: [],
    transaction: {
        account: {},
        budgets: [
            {name: ''}
        ]
    },
    selectedTransaction: {},
    selectedTransactionForAllocation: {},
    selectedBudget: {},
    selectedAccount: {},
    fixedBudgetTotals: {},
    flexBudgetTotals: {},
    transactions: [
        {
            account: {},
            budgets: [
                {name: ''}
            ]
        }
    ],
    fixedBudgets: [],
    flexBudgets: [],
    unassignedBudgets: [],
    favouriteTransactions: [],
    newFavouriteTransaction: {
        account: {},
        fromAccount: {},
        toAccount: {},
        budgets: [],
        type: 'expense'
    },
    selectedFavouriteTransaction: {},
    newTransaction: {
        userDate: 'today',
        type: 'expense',
        account: {},
        fromAccount: {},
        toAccount: {},
        duration: '',
        total: '',
        merchant: '',
        description: '',
        reconciled: false,
        multipleBudgets: false,
        budgets: []
    },
    // newTransactionDefaults: {
    //     userDate: 'today',
    //     type: 'expense',
    //     account: {},
    //     fromAccount: {},
    //     toAccount: {},
    //     duration: '',
    //     total: '',
    //     merchant: '',
    //     description: '',
    //     reconciled: false,
    //     multipleBudgets: false,
    //     budgets: []
    // },
    filters: {

    },
    savedFilters: [],
    //For editing fields in item popup before the item is saved
    selectedItemClone: {

    },
    selectedItem: {

    },
    newItem: {},
    showFilter: true,
    show: {
        popup: {
            'transaction': false,
            'allocation': false,
            'account': false,
            'budget': false,
            'favourite-transaction': false
        },
        newTransaction: true,
        totals: true,
        // basicTotals: false,
        // budgetTotals: false,
        filterTotals: true,
        budget: false,
        filter: true,
        savingsTotal: {
            input: false,
            edit_btn: true
        },
        newBudget: false
    },
    transactionPropertiesToShow: {
        status: false,
        date: true,
        description: true,
        merchant: true,
        total: true,
        type: true,
        account: true,
        duration: true,
        reconciled: true,
        allocated: true,
        budgets: true,
        delete: true,
    }
}