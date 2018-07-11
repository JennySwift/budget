import TotalsRepository from './TotalsRepository'
import TransactionsRepository from './TransactionsRepository'
import helpers from './helpers/Helpers'
import FilterRepository from './FilterRepository'

export default {

    setNewFavouriteTransactionAccount: function () {
        store.set(store.state.accounts[0], 'newFavouriteTransaction.account');
    },

    /**
     *
     */
    setNewTransactionDefaults: function () {
        if (helpers.isLocalEnvironment()) {
            store.set(10, 'newTransaction.total');
            store.set('some merchant', 'newTransaction.merchant');
            store.set('some description', 'newTransaction.description');
            store.set([
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
            ], 'newTransaction.budgets');
        }

        if (store.state.accounts && store.state.accounts.length > 0) {
            store.set(store.state.accounts[0], 'newTransaction.account');
            store.set(store.state.accounts[0], 'newTransaction.toAccount');
            store.set(store.state.accounts[0], 'newTransaction.fromAccount');
        }
    },
    
    /**
     *
     */
    insertTransactionSetup: function () {
        if (!this.isInvalidData()) {
            TotalsRepository.resetTotalChanges();

            if (store.state.newTransaction.type === 'transfer') {
                var that = this;
                this.insertTransaction('from');
                setTimeout(function(){
                    that.insertTransaction('to');
                }, 100);
            }
            else {
                this.insertTransaction();
            }
        }
    },

    /**
     * Direction can be 'from' or 'to' for a transfer transaction, or undefined for a credit or debit transaction
     * @param direction
     */
    insertTransaction: function (direction) {
        var data = TransactionsRepository.setFields(store.state.newTransaction, direction);

        helpers.post({
            url: '/api/transactions',
            data: data,
            message: 'Transaction created',
            clearFields: this.clearFields,
            callback: function (response) {
                TotalsRepository.getSideBarTotals(this);
                this.clearNewTransactionFields();

                if (response.multipleBudgets) {
                    TransactionsRepository.showAllocationPopup(response);
                }
                else {
                    FilterRepository.runFilter(this);
                }
            }.bind(this)
        });
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
     *
     * @returns {*}
     */
    isInvalidData: function () {
        var errorMessages = [];

        //if (!Date.parse(newTransaction.userDate)) {
        //    messages.push('Date is not valid');
        //}
        //else {
        //    newTransaction.date = Date.parse(newTransaction.userDate).toString('yyyy-MM-dd');
        //}

        if (store.state.newTransaction.total === "") {
            errorMessages.push('Total is required');
        }
        else if (!$.isNumeric(store.state.newTransaction.total)) {
            errorMessages.push('Total is not a valid number');
        }

        if (errorMessages.length > 0) {
            helpers.provideFeedback(errorMessages, 'error');

            return errorMessages;
        }

        return false;
    },

}