var FavouriteTransactionsRepository = {

    /**
     *
     * @param favouriteTransaction
     * @returns {{name: *, type: *, description: *, merchant: *, total: *, budget_ids}}
     */
    setFields: function (favouriteTransaction) {
        var data = {
            name: favouriteTransaction.name,
            type: favouriteTransaction.type,
            description: favouriteTransaction.description,
            merchant: favouriteTransaction.merchant,
            total: favouriteTransaction.total,
            budget_ids: _.pluck(favouriteTransaction.budgets, 'id')
        };

        if (favouriteTransaction.account && favouriteTransaction.type !== 'transfer') {
            data.account_id = favouriteTransaction.account.id;
        }

        if (favouriteTransaction.fromAccount && favouriteTransaction.type === 'transfer') {
            data.from_account_id = favouriteTransaction.fromAccount.id;
        }

        if (favouriteTransaction.toAccount && favouriteTransaction.type === 'transfer') {
            data.to_account_id = favouriteTransaction.toAccount.id;
        }

        return data;
    }
};