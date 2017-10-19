import TotalsRepository from './TotalsRepository'
import TransactionsRepository from './TransactionsRepository'
import helpers from './Helpers'

export default {


    /**
     *
     */
    insertTransaction: function (direction) {
        if (!this.anyErrors()) {
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
     * Return true if there are errors.
     * @returns {boolean}
     */
    anyErrors: function () {
        var errorMessages = store.anyNewTransactionErrors(this.shared.newTransaction);

        if (errorMessages) {
            for (var i = 0; i < errorMessages.length; i++) {
                $.event.trigger('provide-feedback', [errorMessages[i], 'error']);
            }

            return true;
        }

        return false;
    },

}