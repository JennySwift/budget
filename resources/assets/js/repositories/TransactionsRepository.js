var TransactionsRepository = {
    totals: {},

    /**
     *
     * @param transaction
     * @returns {{date: (*|newTransaction.date|{}|NewTransactionRepository.defaults.date|{entered}|string), account_id: (*|number), description: *, merchant: *, total: *, reconciled: (*|number|string|boolean), allocated: *, minutes: *, budgets: *}}
     */
    setFields: function (transaction) {
        return {
            date: HelpersRepository.formatDate(transaction.userDate),
            account_id: transaction.account.id,
            description: transaction.description,
            merchant: transaction.merchant,
            total: transaction.total,
            reconciled: transaction.reconciled,
            allocated: transaction.allocated,
            minutes: transaction.minutes,
            budgets: transaction.budgets,
        }
    },

    insertIncomeOrExpenseTransaction: function ($newTransaction) {
        var $url = '/api/transactions';

        if ($newTransaction.type === 'expense' && $newTransaction.total > 0) {
            //transaction is an expense without the negative sign
            $newTransaction.total*= -1;
        }

        //Convert duration from HH:MM format to minutes
        $newTransaction.minutes = moment.duration($newTransaction.duration).asMinutes();

        return $http.post($url, $newTransaction);
    },

    insertTransferTransaction: function ($newTransaction, $direction) {
        var $url = '/api/transactions';
        var $data = $newTransaction;

        $data.direction = $direction;

        if ($direction === 'from') {
            $data.account_id = $data.from_account_id;
        }
        else if ($direction === 'to') {
            $data.account_id = $data.to_account_id;
        }

        return $http.post($url, $data);
    },

    updateMassTags: function ($tag_array, $url, $tag_location) {
        var $transaction_id;

        var $tag_id_array = $tag_array.map(function (el) {
            return el.tag_id;
        });

        $tag_id_array = JSON.stringify($tag_id_array);

        $(".checked").each(function () {
            $transaction_id = $(this).closest("tbody").attr('id');
            var $url = 'api/update/massTags';
            var $description = 'mass edit tags';
            var $data = {
                description: $description,
                transaction_id: $transaction_id,
                tag_id_array: $tag_id_array
            };

            return $http.post($url, $data);
        });
    },

    massEditDescription: function () {
        var $transaction_id;
        var $description = $("#mass-edit-description-input").val();
        var $info = {
            transaction_id: $transaction_id,
            description: $description
        };

        $(".checked").each(function () {
            $transaction_id = $(this).closest("tbody").attr('id');

            var $url = 'api/update/massDescription';
            var $data = {
                info: $info
            };

            return $http.post($url, $data);
        });
    },

    updateTransaction: function ($transaction) {
        var $url = $transaction.path;

        $transaction.date = Date.parse($("#edit-transaction-date").val()).toString('yyyy-MM-dd');

        //Make sure total is negative for an expense transaction
        if ($transaction.type === 'expense' && $transaction.total > 0) {
            $transaction.total = $transaction.total * -1;
        }

        //Convert duration from HH:MM format to minutes
        $transaction.minutes = moment.duration($transaction.duration).asMinutes();

        return $http.put($url, $transaction);
    },

    updateReconciliation: function ($transaction) {
        var $url = $transaction.path;
        //So the reconciled value doesn't change the checkbox for the front-end
        var $data = {reconciled: 0};

        if ($transaction.reconciled) {
            $data.reconciled = 1;
        }

        return $http.put($url, $data);
    },

    deleteTransaction: function ($transaction) {
        var $url = $transaction.path;

        return $http.delete($url);
    },

    massDelete: function () {
        $(".checked").each(function () {
            deleteTransaction($(this));
        });
    },

    getAllocationTotals: function ($transaction_id) {
        var $url = 'api/select/allocationTotals';
        var $data = {
            transaction_id: $transaction_id
        };

        return $http.post($url, $data);
    },

    updateAllocation: function ($type, $value, $transaction_id, $budget_id) {
        var $url = 'api/updateAllocation';
        var $data = {
            type: $type,
            value: $value,
            transaction_id: $transaction_id,
            budget_id: $budget_id
        };

        return $http.post($url, $data);
    },

    updateAllocationStatus: function ($transaction) {
        var $url = $transaction.path;
        var $data = {
            allocated: $transaction.allocated
        };

        return $http.put($url, $data);
    },

};