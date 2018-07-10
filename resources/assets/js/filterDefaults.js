export default {
    total: {
        in: "",
        out: ""
    },
    types: {
        in: [],
        out: []
    },
    accounts: {
        in: [],
        out: []
    },
    singleDate: {
        in: '',
        out: ''
    },
    fromDate: {
        in: '',
        out: ''
    },
    toDate: {
        in: '',
        out: ''
    },
    description: {
        in: "",
        out: ""
    },
    merchant: {
        in: "",
        out: ""
    },
    budgets: {
        in: {
            and: [],
            or: []
        },
        out: []
    },
    numBudgets: {
        in: "all",
        out: ""
    },
    reconciled: "any",
    invalidAllocation: false,
    offset: 0,
    numToFetch: 30,
    displayFrom: 1,
    displayTo: 30
}