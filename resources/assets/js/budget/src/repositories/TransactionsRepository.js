import FilterRepository from './FilterRepository.js'
import helpers from './Helpers'
export default {
    totals: {},

    /**
     *
     */
    filterTransactions: function () {
        var data = {
            filter: FilterRepository.formatDates()
        };

        helpers.get({
            url: '/api/filter/transactions',
            data: data,
            storeProperty: 'transactions'
        });
    },

    state: {

    },


    /**
     *
     * @param transaction
     * @param direction
     * @returns {{date: *, account_id: number, type: *, description: *, merchant: (*|string|filter.merchant|{in, out}|boolean|state.filter.merchant), total: *, reconciled, allocated: (*|boolean), minutes: *, budget_ids: Array}}
     */
    setFields: function (transaction, direction) {
        var data = {
            date: helpers.convertToMySqlDate(transaction.userDate),
            account_id: transaction.account.id,
            type: transaction.type,
            description: transaction.description,
            merchant: transaction.merchant,
            total: transaction.total,
            reconciled: helpers.convertBooleanToInteger(transaction.reconciled),
            allocated: transaction.allocated,
            //Convert duration from HH:MM format to minutes
            minutes: helpers.formatDurationToMinutes(transaction.duration),
            budget_ids: _.map(transaction.budgets, 'id')
        };

        if (transaction.type === 'expense' && transaction.total > 0) {
            //transaction is an expense without the negative sign
            data.total*= -1;
        }

        if (direction) {
            //It is a transfer transaction
            data.direction = direction;

            if (direction === 'from') {
                data.account_id = store.state.newTransaction.fromAccount.id;
            }
            else if (direction === 'to') {
                data.account_id = store.state.newTransaction.toAccount.id;
            }
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

    /**
    *
    * @param transaction
    */
    deleteTransaction: function (transaction) {
        var index = helpers.findIndexById(this.state.transactions, transaction.id);
        this.state.transactions = _.without(this.state.transactions, this.state.transactions[index]);
    },

    massDelete: function () {
        $(".checked").each(function () {
            deleteTransaction($(this));
        });
    }

};