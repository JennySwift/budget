var NewTransactionRepository = {

    defaults: {
        type: 'income',
        account_id: 1,
        date: {
            entered: 'today'
        },
        merchant: '',
        description: '',
        reconciled: false,
        multiple_budgets: false,
        budgets: []
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
            this.defaults.total = 10;
            this.defaults.type = 'expense';
            this.defaults.date.entered = 'today';
            this.defaults.merchant = 'some merchant';
            this.defaults.description = 'some description';
            this.defaults.duration = '';
            this.defaults.budgets = [
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
            this.defaults.account_id = accounts[0].id;
            this.defaults.from_account_id = accounts[0].id;
            this.defaults.to_account_id = accounts[0].id;
        }
    
        return this.defaults;
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

    anyErrors: function ($newTransaction) {
        var $messages = [];

        if (!Date.parse($newTransaction.date.entered)) {
            $messages.push('Date is not valid');
        }
        else {
            $newTransaction.date.sql = Date.parse($newTransaction.date.entered).toString('yyyy-MM-dd');
        }

        if ($newTransaction.total === "") {
            $messages.push('Total is required');
        }
        else if (!$.isNumeric($newTransaction.total)) {
            $messages.push('Total is not a valid number');
        }

        if ($messages.length > 0) {
            return $messages;
        }

        return false;
    }
};