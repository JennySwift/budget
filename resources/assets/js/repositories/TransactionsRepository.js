var TransactionsRepository = {
    totals: {},

    /**
     *
     * @param transaction
     * @returns {{date: (*|newTransaction.date|{}|NewTransactionRepository.defaults.date|{entered}|string), account_id: (*|number), description: *, merchant: *, total: *, reconciled: (*|number|string|boolean), allocated: *, minutes: *, budgets: *}}
     */
    setFields: function (transaction) {
        var data = {
            date: HelpersRepository.formatDate(transaction.userDate),
            account_id: transaction.account.id,
            type: transaction.type,
            description: transaction.description,
            merchant: transaction.merchant,
            total: transaction.total,
            reconciled: HelpersRepository.convertBooleanToInteger(transaction.reconciled),
            allocated: transaction.allocated,
            //Convert duration from HH:MM format to minutes
            minutes: HelpersRepository.formatDurationToMinutes(transaction.duration),
            budget_ids: _.pluck(transaction.budgets, 'id')
        };

        if (transaction.type === 'expense' && transaction.total > 0) {
            //transaction is an expense without the negative sign
            data.total*= -1;
        }

        return data;
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

    massDelete: function () {
        $(".checked").each(function () {
            deleteTransaction($(this));
        });
    }

};