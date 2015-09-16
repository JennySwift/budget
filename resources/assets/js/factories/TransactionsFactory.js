app.factory('TransactionsFactory', function ($http) {
    var $object = {};
    $object.totals = {};

    $object.insertIncomeOrExpenseTransaction = function ($newTransaction) {
        var $url = '/api/transactions';

        if ($newTransaction.type === 'expense' && $newTransaction.total > 0) {
            //transaction is an expense without the negative sign
            $newTransaction.total*= -1;
        }

        return $http.post($url, $newTransaction);
    };

    $object.insertTransferTransaction = function ($newTransaction, $direction) {
        var $url = '/api/transactions';

        $newTransaction.direction = $direction;

        if ($direction === 'from') {
            $newTransaction.account_id = $newTransaction.from_account_id;
        }
        else if ($direction === 'to') {
            $newTransaction.account_id = $newTransaction.to_account_id;
        }

        return $http.post($url, $newTransaction);
    };

    $object.updateMassTags = function ($tag_array, $url, $tag_location) {
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
    };

    $object.massEditDescription = function () {
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
    };

    $object.updateTransaction = function ($transaction, $filter) {
        var $url = $transaction.path;

        //Make sure total is negative for an expense transaction
        if ($transaction.type === 'expense' && $transaction.total > 0) {
            $transaction.total = $transaction.total * -1;
        }

        var $data = {
            transaction: $transaction,
            filter: $filter
        };

        return $http.put($url, $data);
    };

    $object.updateReconciliation = function ($transaction_id, $reconciled, $filter) {
        var $url = 'api/updateReconciliation';

        if ($reconciled === true) {
            $reconciled = 'true';
        }
        else {
            $reconciled = 'false';
        }

        var $data = {
            id: $transaction_id,
            reconciled: $reconciled,
            filter: $filter
        };

        return $http.post($url, $data);
    };

    $object.deleteTransaction = function ($transaction) {
        var $url = $transaction.path;

        return $http.delete($url);
    };

    $object.massDelete = function () {
        $(".checked").each(function () {
            deleteTransaction($(this));
        });
    };

    $object.countTransactionsWithBudget = function ($budget) {
        var $url = '/api/select/countTransactionsWithBudget';

        var $data = {
            budget_id: $budget.id
        };

        return $http.post($url, $data);
    };

    $object.getAllocationTotals = function ($transaction_id) {
        var $url = 'api/select/allocationTotals';
        var $data = {
            transaction_id: $transaction_id
        };

        return $http.post($url, $data);
    };

    $object.updateAllocation = function ($type, $value, $transaction_id, $budget_id) {
        var $url = 'api/updateAllocation';
        var $data = {
            type: $type,
            value: $value,
            transaction_id: $transaction_id,
            budget_id: $budget_id
        };

        return $http.post($url, $data);
    };

    $object.updateAllocationStatus = function ($transaction_id, $status) {
        var $url = 'api/updateAllocationStatus';
        var $data = {
            transaction_id: $transaction_id,
            status: $status
        };

        return $http.post($url, $data);
    };


    return $object;
});
