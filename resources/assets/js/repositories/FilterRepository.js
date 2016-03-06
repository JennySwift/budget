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

    formatDates: function () {
        if (this.filter.singleDate.in) {
            this.filter.singleDate.inSql = HelpersRepository.formatDate(this.filter.singleDate.in);
        }
        else {
            this.filter.singleDate.inSql = "";
        }
        if (this.filter.singleDate.out) {
            this.filter.singleDate.outSql = HelpersRepository.formatDate(this.filter.singleDate.out);
        }
        else {
            this.filter.singleDate.outSql = "";
        }
        if (this.filter.fromDate.in) {
            this.filter.fromDate.inSql = HelpersRepository.formatDate(this.filter.fromDate.in);
        }
        else {
            this.filter.fromDate.inSql = "";
        }
        if (this.filter.fromDate.out) {
            this.filter.fromDate.outSql = HelpersRepository.formatDate(this.filter.fromDate.out);
        }
        else {
            this.filter.fromDate.outSql = "";
        }
        if (this.filter.toDate.in) {
            this.filter.toDate.inSql = HelpersRepository.formatDate(this.filter.toDate.in);
        }
        else {
            this.filter.toDate.inSql = "";
        }
        if (this.filter.toDate.out) {
            this.filter.toDate.outSql = HelpersRepository.formatDate(this.filter.toDate.out);
        }
        else {
            this.filter.toDate.outSql = "";
        }

        return this.filter;
    },

};

FilterRepository.resetFilter();