var NewTransactionRepository = {

    state: {
        defaults: {
            userDate: 'today',
            type: 'expense',
            duration: '',
            total: '',
            merchant: '',
            description: '',
            reconciled: false,
            multipleBudgets: false,
            budgets: []
        }
    },

    /**
     *
     * @param env
     * @param accounts
     * @returns {NewTransactionRepository.defaults|{type, account_id, date, merchant, description, reconciled, multiple_budgets, budgets}}
     */
    getDefaults: function (env, accounts) {
        //Fill in the new transaction fields if development environment
        if (env === 'local') {
            this.state.defaults.total = 10;
            this.state.defaults.merchant = 'some merchant';
            this.state.defaults.description = 'some description';
            this.state.defaults.budgets = [
                {
                    id: '2',
                    name: 'business',
                    type: 'fixed'
                },
                //{
                //    id: '4',
                //    name: 'busking',
                //    type: 'flex'
                //}
            ];
        }
    
        if (accounts.length > 0) {
            this.state.defaults.account = accounts[0];
            this.state.defaults.fromAccount = accounts[0];
            this.state.defaults.toAccount = accounts[0];
        }

        return this.state.defaults;
    },

    /**
     *
     * @param env
     * @param me
     * @param newTransaction
     * @returns {*}
     */
    clearFields: function (env, me, newTransaction) {
        if (me.preferences.clearFields) {
            newTransaction.budgets = [];
            newTransaction.total = '';
            newTransaction.description = '';
            newTransaction.merchant = '';
            newTransaction.reconciled = false;
            newTransaction.multipleBudgets = false;
        }

        return newTransaction;
    },


    /**
     *
     * @param newTransaction
     * @returns {*}
     */
    anyErrors: function (newTransaction) {
        var messages = [];

        //if (!Date.parse(newTransaction.userDate)) {
        //    messages.push('Date is not valid');
        //}
        //else {
        //    newTransaction.date = Date.parse(newTransaction.userDate).toString('yyyy-MM-dd');
        //}

        if (newTransaction.total === "") {
            messages.push('Total is required');
        }
        else if (!$.isNumeric(newTransaction.total)) {
            messages.push('Total is not a valid number');
        }

        if (messages.length > 0) {
            return messages;
        }

        return false;
    }
};