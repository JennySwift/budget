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

        if (favouriteTransaction.account) {
            data.account_id = favouriteTransaction.account.id;
        }

        return data;
    }
};