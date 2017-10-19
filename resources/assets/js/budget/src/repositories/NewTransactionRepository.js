import TotalsRepository from './TotalsRepository'
import TransactionsRepository from './TransactionsRepository'
import helpers from './Helpers'
import FilterRepository from './FilterRepository'

export default {


    /**
     *
     */
    insertTransaction: function (direction) {
        if (!this.showErrorsIfExist()) {
            TotalsRepository.resetTotalChanges();

            if (this.newTransaction.type === 'transfer') {
                var that = this;
                this.insertTransaction('from');
                setTimeout(function(){
                    that.insertTransaction('to');
                }, 100);
            }
            else {
                var data = TransactionsRepository.setFields(this.newTransaction);

                if (direction) {
                    //It is a transfer transaction
                    data.direction = direction;

                    if (direction === 'from') {
                        data.account_id = this.newTransaction.fromAccount.id;
                    }
                    else if (direction === 'to') {
                        data.account_id = this.newTransaction.toAccount.id;
                    }
                }

                helpers.post({
                    url: '/api/transactions',
                    data: data,
                    message: 'Transaction created',
                    clearFields: this.clearFields,
                    callback: function (response) {
                        this.insertTransactionResponse(response);
                    }.bind(this)
                });
            }
        }
    },

    /**
     *
     * @param response
     */
    insertTransactionResponse: function (response) {
        TotalsRepository.getSideBarTotals(this);
        this.clearNewTransactionFields();
        //this.newTransaction.dropdown = false;

        if (response.multipleBudgets) {
            $.event.trigger('show-allocation-popup', [response, true]);
            //We'll run the filter after the allocation has been dealt with
        }
        else {
            FilterRepository.runFilter(this);
        }
    },

    /**
     *
     */
    clearNewTransactionFields: function () {
        if (store.state.me.preferences.clearFields) {
            store.set([], 'newTransaction.budgets');
            store.set('', 'newTransaction.total');
            store.set('', 'newTransaction.description');
            store.set('', 'newTransaction.merchant');
            store.set(false, 'newTransaction.reconciled');
            store.set(false, 'newTransaction.multipleBudgets');
        }
    },

    /**
     * Return true if there are errors.
     * @returns {boolean}
     */
    showErrorsIfExist: function () {
        var errorMessages = this.anyNewTransactionErrors();

        if (errorMessages) {
            for (var i = 0; i < errorMessages.length; i++) {
                $.event.trigger('provide-feedback', [errorMessages[i], 'error']);
            }

            return true;
        }

        return false;
    },

    /**
     *
     * @returns {*}
     */
    anyNewTransactionErrors: function () {
        var messages = [];

        //if (!Date.parse(newTransaction.userDate)) {
        //    messages.push('Date is not valid');
        //}
        //else {
        //    newTransaction.date = Date.parse(newTransaction.userDate).toString('yyyy-MM-dd');
        //}

        if (store.state.newTransaction.total === "") {
            messages.push('Total is required');
        }
        else if (!$.isNumeric(store.state.newTransaction.total)) {
            messages.push('Total is not a valid number');
        }

        if (messages.length > 0) {
            return messages;
        }

        return false;
    },

}