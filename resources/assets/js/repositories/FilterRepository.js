var FilterRepository = {

    resetFilter: function () {
        this.filter = {

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
            offset: 0,
            numToFetch: 30,
            displayFrom: 1,
            displayTo: 30
        };

        $.event.trigger('reset-filter');

        return this.filter;
    },

    formatDates: function (filter) {
        if (filter.singleDate.in) {
            filter.singleDate.inSql = HelpersRepository.formatDate(filter.singleDate.in);
        }
        else {
            filter.singleDate.inSql = "";
        }
        if (filter.singleDate.out) {
            filter.singleDate.outSql = HelpersRepository.formatDate(filter.singleDate.out);
        }
        else {
            filter.singleDate.outSql = "";
        }
        if (filter.fromDate.in) {
            filter.fromDate.inSql = HelpersRepository.formatDate(filter.fromDate.in);
        }
        else {
            filter.fromDate.inSql = "";
        }
        if (filter.fromDate.out) {
            filter.fromDate.outSql = HelpersRepository.formatDate(filter.fromDate.out);
        }
        else {
            filter.fromDate.outSql = "";
        }
        if (filter.toDate.in) {
            filter.toDate.inSql = HelpersRepository.formatDate(filter.toDate.in);
        }
        else {
            filter.toDate.inSql = "";
        }
        if (filter.toDate.out) {
            filter.toDate.outSql = HelpersRepository.formatDate(filter.toDate.out);
        }
        else {
            filter.toDate.outSql = "";
        }

        return filter;
    },

};

FilterRepository.resetFilter();