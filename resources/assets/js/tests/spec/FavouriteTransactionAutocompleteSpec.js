var newTransaction = new Vue(newTransaction);

describe('favourite-transaction-autocomplete', function () {
    it('has the correct default data for a new transaction', function () {
        //Check the initial data
        expect(newTransaction.types).toEqual([
            'income', 'expense', 'transfer'
        ]);

        //Check the default new transaction fields are correct
        expect(newTransaction.newTransaction.userDate).toEqual('today');
        expect(newTransaction.newTransaction.type).toEqual('expense');
        expect(newTransaction.newTransaction.account).toEqual({});
        expect(newTransaction.newTransaction.duration).toEqual('');
        expect(newTransaction.newTransaction.total).toEqual('');
        expect(newTransaction.newTransaction.merchant).toEqual('');
        expect(newTransaction.newTransaction.description).toEqual('');
        expect(newTransaction.newTransaction.reconciled).toEqual(false);
        expect(newTransaction.newTransaction.multipleBudgets).toEqual(false);
        expect(newTransaction.newTransaction.budgets).toEqual([]);


        //Why undefined?
        // console.log($("#new-transaction-account").val());
    });

    it('fills the fields for the new transaction', function () {
        newTransaction.selectedFavouriteTransaction.description = 'abc';
        newTransaction.selectedFavouriteTransaction.merchant = 'koala';
        newTransaction.selectedFavouriteTransaction.total = '25';
        newTransaction.selectedFavouriteTransaction.type = 'income';
        newTransaction.selectedFavouriteTransaction.budgets = [];
        newTransaction.selectedFavouriteTransaction.account = {id: 1, name: 'business'};

        newTransaction.fillFields();

        expect(newTransaction.newTransaction.description).toEqual('abc');
        expect(newTransaction.newTransaction.merchant).toEqual('koala');
        expect(newTransaction.newTransaction.total).toEqual('25');
        expect(newTransaction.newTransaction.type).toEqual('income');
        expect(newTransaction.newTransaction.budgets).toEqual([]);

        //This test is basically useless, because it was passing when the value of the select element wasn't changing in the browser,
        //due to the objects not being equal. Not sure how to test the select element value.
        expect(newTransaction.newTransaction.account).toEqual({id: 1, name: 'business'});
    });
});