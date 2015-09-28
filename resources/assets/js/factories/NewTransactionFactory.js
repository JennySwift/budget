app.factory('NewTransactionFactory', function ($http) {
    var $object = {};

    var $defaults = {
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
    };

    $object.getDefaults = function ($env, $accounts) {
        /**
         * Fill in the new transaction fields if development environment
         */
        if ($env === 'local') {
            $defaults.total = 10;
            $defaults.type = 'expense';
            $defaults.date.entered = 'today';
            $defaults.merchant = 'some merchant';
            $defaults.description = 'some description';
            $defaults.budgets = [
                {
                    id: '2',
                    name: 'business',
                    type: 'fixed'
                },
                {
                    id: '4',
                    name: 'busking',
                    type: 'flex'
                }
            ];
        }

        if ($accounts.length > 0) {
            $defaults.account_id = $accounts[0].id;
            $defaults.from_account_id = $accounts[0].id;
            $defaults.to_account_id = $accounts[0].id;
        }

        return $defaults;
    };

    $object.clearFields = function (env, me, $newTransaction) {
        if (env !== 'local') {
            $newTransaction.budgets = [];
        }

        if (me.preferences.clearFields) {
            $newTransaction.total = '';
            $newTransaction.description = '';
            $newTransaction.merchant = '';
            $newTransaction.reconciled = false;
            $newTransaction.multiple_budgets = false;
        }

        return $newTransaction;
    };

    return $object;
});